# Установка kagglehub и импорт библиотек
!pip install kagglehub -q

import kagglehub
import os
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
import warnings
warnings.filterwarnings('ignore')

# Настройка визуализации
plt.style.use('seaborn-v0_8-darkgrid')
sns.set_palette("Set2")
plt.rcParams['figure.figsize'] = (12, 6)

print("Все библиотеки импортированы")

# Загрузка датасета через kagglehub
print(" Загрузка датасета Corporación Favorita через kagglehub")

# Загрузка датасета (автоматически скачивается и кэшируется)
path = kagglehub.dataset_download("ruiyuanfan/corporacin-favorita-grocery-sales-forecasting")

print(" Датасет загружен в", path)

# Просмотр файлов в датасете
print("\n Файлы в датасете:")
for file in os.listdir(path):
    size = os.path.getsize(os.path.join(path, file)) / (1024 * 1024)  # размер в MB
    print(f"   {file} ({size:.2f} MB)")

# Загрузка данных с оптимизацией
# Пути к файлам
train_path = os.path.join(path, 'train.csv')
holidays_path = os.path.join(path, 'holidays_events.csv')

# Проверяем существует ли файл
if not os.path.exists(train_path):
    print(" Файл не найден:", train_path)
else:
    print("Файл найден")

# Загружаем train.csv (без указания dtype для onpromotion, чтобы потом обработать пропуски)
print("Загрузка train.csv")

# Сначала загружаем только нужные колонки
usecols = ['date', 'store_nbr', 'item_nbr', 'unit_sales', 'onpromotion']

df = pd.read_csv(
    train_path,
    usecols=usecols,
    parse_dates=['date']
)

print("Загружено записей:",len(df))
print("Память до оптимизации (MB):", df.memory_usage(deep=True).sum() / 1024**2)

# Обработка пропусков в onpromotion
print("\nОбработка пропусков в onpromotion")
print("   Пропусков до обработки:", df['onpromotion'].isnull().sum())

# Заполняем пропуски значением False (товар не на промо)
df['onpromotion'] = df['onpromotion'].fillna(False)

# Преобразуем в bool
df['onpromotion'] = df['onpromotion'].astype('bool')

print("   Пропусков после обработки:", df['onpromotion'].isnull().sum())

# Оптимизация типов
df['store_nbr'] = df['store_nbr'].astype('int8')
df['item_nbr'] = df['item_nbr'].astype('int32')
df['unit_sales'] = df['unit_sales'].astype('float32')

print(" Память после оптимизации (MB):", df.memory_usage(deep=True).sum() / 1024**2)

# Создание представительной выборки
df_sample = df.sample(frac=0.1, random_state=42)
print("Выборка :", len(df_sample))
print("Память выборки (MB):", df_sample.memory_usage(deep=True).sum() / 1024**2)

# Освобождаем исходные данные
del df
import gc
gc.collect()
print("Исходные данные удалены")

# Добавление данных о праздниках

# Проверяем существование файла с праздниками
if os.path.exists(holidays_path):
    holidays_df = pd.read_csv(holidays_path, parse_dates=['date'])
    print("Загружено записей о праздниках:", len(holidays_df))

    # Добавляем флаг праздника
    holidays_df['is_holiday'] = 1
    df_sample = df_sample.merge(
        holidays_df[['date', 'is_holiday']],
        on='date',
        how='left'
    )
    df_sample['is_holiday'] = df_sample['is_holiday'].fillna(0).astype('int8')
    print("Флаг праздника добавлен")
else:
    print("Файл с праздниками не найден, флаг по умолчанию")
    df_sample['is_holiday'] = 0

# EDA

print("\nСтатистика продаж:")
print(df_sample['unit_sales'].describe())

# График продаж по дням
daily_sales = df_sample.groupby('date')['unit_sales'].sum().reset_index()

plt.figure(figsize=(15, 5))
plt.plot(daily_sales['date'], daily_sales['unit_sales'], linewidth=1)
plt.title('Ежедневные продажи')
plt.xlabel('Дата')
plt.ylabel('Продажи')
plt.xticks(rotation=45)
plt.tight_layout()
plt.show()

# Блок 8: Feature Engineering
# Сортировка для создания лагов
df_sample = df_sample.sort_values(['store_nbr', 'item_nbr', 'date'])

# Временные признаки
df_sample['year'] = df_sample['date'].dt.year.astype('int16')
df_sample['month'] = df_sample['date'].dt.month.astype('int8')
df_sample['day'] = df_sample['date'].dt.day.astype('int8')
df_sample['day_of_week'] = df_sample['date'].dt.dayofweek.astype('int8')
df_sample['quarter'] = df_sample['date'].dt.quarter.astype('int8')
df_sample['is_weekend'] = (df_sample['day_of_week'] >= 5).astype('int8')

# Циклическое кодирование
df_sample['month_sin'] = np.sin(2 * np.pi * df_sample['month'] / 12).astype('float32')
df_sample['month_cos'] = np.cos(2 * np.pi * df_sample['month'] / 12).astype('float32')
df_sample['day_sin'] = np.sin(2 * np.pi * df_sample['day_of_week'] / 7).astype('float32')
df_sample['day_cos'] = np.cos(2 * np.pi * df_sample['day_of_week'] / 7).astype('float32')

print("Временные признаки созданы")

# Лаговые признаки
print("Создание лаговых признаков...")
df_sample['lag_1'] = df_sample.groupby(['store_nbr', 'item_nbr'])['unit_sales'].shift(1).astype('float32')
df_sample['lag_7'] = df_sample.groupby(['store_nbr', 'item_nbr'])['unit_sales'].shift(7).astype('float32')
df_sample['lag_14'] = df_sample.groupby(['store_nbr', 'item_nbr'])['unit_sales'].shift(14).astype('float32')
df_sample['lag_28'] = df_sample.groupby(['store_nbr', 'item_nbr'])['unit_sales'].shift(28).astype('float32')

# Скользящие средние
df_sample['roll_7'] = df_sample.groupby(['store_nbr', 'item_nbr'])['unit_sales'].transform(
    lambda x: x.shift(1).rolling(7).mean()
).astype('float32')
df_sample['roll_14'] = df_sample.groupby(['store_nbr', 'item_nbr'])['unit_sales'].transform(
    lambda x: x.shift(1).rolling(14).mean()
).astype('float32')

print("Лаговые признаки созданы")

# Удаление пропусков
initial_len = len(df_sample)
df_sample = df_sample.dropna().copy()
print("Удалено записей с пропусками:",initial_len - len(df_sample))
print("Итоговый размер:", len(df_sample))

# Подготовка данных для обучения
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.model_selection import train_test_split

# Выбор признаков
features = [
    'store_nbr', 'item_nbr', 'onpromotion', 'is_holiday',
    'month', 'day_of_week', 'is_weekend',
    'month_sin', 'month_cos', 'day_sin', 'day_cos',
    'lag_1', 'lag_7', 'lag_14', 'lag_28',
    'roll_7', 'roll_14'
]

# Кодирование категориальных признаков
le_store = LabelEncoder()
le_item = LabelEncoder()

df_sample['store_enc'] = le_store.fit_transform(df_sample['store_nbr'].astype(str))
df_sample['item_enc'] = le_item.fit_transform(df_sample['item_nbr'].astype(str))

# Обновляем список признаков
features = ['store_enc', 'item_enc'] + [f for f in features if f not in ['store_nbr', 'item_nbr']]

X = df_sample[features].values
y = df_sample['unit_sales'].values

print("Матрица признаков X:", X.shape)
print("Память X (MB):", X.nbytes / 1024**2)

# Разделение данных
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

print("Обучающая выборка:", len(X_train))
print("Тестовая выборка:", len(X_test))

# Нормализация
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

print("Данные нормализованы")

# Обучение моделей

from sklearn.linear_model import Ridge
from sklearn.tree import DecisionTreeRegressor
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error, mean_squared_error
import xgboost as xgb
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Dropout
from tensorflow.keras.callbacks import EarlyStopping
from tensorflow.keras.optimizers import Adam

def calculate_metrics(y_true, y_pred, model_name):
    """Расчет 4 основных метрик"""
    mae = mean_absolute_error(y_true, y_pred)
    rmse = np.sqrt(mean_squared_error(y_true, y_pred))
    mse = mean_squared_error(y_true, y_pred)
    # SMAPE
    numerator = np.abs(y_true - y_pred)
    denominator = ((y_true) + (y_pred))/2
    smape = np.mean(numerator / denominator) * 100

    print(f" {model_name}")
    print(f"   MAE:   {mae:.4f}")
    print(f"   RMSE:  {rmse:.4f}")
    print(f"   SMAPE: {smape:.2f}%")
    print(f"   MSE:    {mse:.4f}")

    return {
        'Model': model_name,
        'MAE': mae,
        'RMSE': rmse,
        'SMAPE (%)': smape,
        'MSE': mse
    }

# Словари для хранения результатов
all_predictions = {}
all_models = {}
results = []

# 1. Ridge Regression
print("\n 1. Ridge Regression")
ridge = Ridge(alpha=1.0, random_state=42)
ridge.fit(X_train_scaled, y_train)
y_pred = ridge.predict(X_test_scaled)
all_predictions['Ridge'] = y_pred
all_models['Ridge'] = ridge
results.append(calculate_metrics(y_test, y_pred, "Ridge Regression"))

# 2. Decision Tree
print("\n 2. Decision Tree")
dt = DecisionTreeRegressor(max_depth=10, min_samples_split=20, random_state=42)
dt.fit(X_train_scaled, y_train)
y_pred = dt.predict(X_test_scaled)
all_predictions['Decision Tree'] = y_pred
all_models['Decision Tree'] = dt
results.append(calculate_metrics(y_test, y_pred, "Decision Tree"))

# 3. Random Forest
print("\n 3. Random Forest")
rf = RandomForestRegressor(
    n_estimators=50,
    max_depth=10,
    min_samples_split=20,
    n_jobs=-1,
    random_state=42
)
rf.fit(X_train_scaled, y_train)
y_pred = rf.predict(X_test_scaled)
all_predictions['Random Forest'] = y_pred
all_models['Random Forest'] = rf
results.append(calculate_metrics(y_test, y_pred, "Random Forest"))

# 4. XGBoost
print("\n 4. XGBoost")
xgb_model = xgb.XGBRegressor(
    n_estimators=100,
    max_depth=6,
    learning_rate=0.1,
    subsample=0.8,
    colsample_bytree=0.8,
    tree_method='hist',
    random_state=42
)
xgb_model.fit(X_train_scaled, y_train)
y_pred = xgb_model.predict(X_test_scaled)
all_predictions['XGBoost'] = y_pred
all_models['XGBoost'] = xgb_model
results.append(calculate_metrics(y_test, y_pred, "XGBoost"))

# 5. Neural Network (MLP)
print("\n 5. Neural Network (MLP)")
mlp_model = Sequential([
    Dense(128, activation='relu', input_shape=(X_train_scaled.shape[1],)),
    Dropout(0.2),
    Dense(64, activation='relu'),
    Dropout(0.2),
    Dense(32, activation='relu'),
    Dense(1, activation='linear')
])

mlp_model.compile(optimizer=Adam(learning_rate=0.001), loss='mse', metrics=['mae'])
early_stop = EarlyStopping(patience=10, restore_best_weights=True, verbose=0)

history = mlp_model.fit(
    X_train_scaled, y_train,
    validation_split=0.1,
    epochs=50,
    batch_size=256,
    callbacks=[early_stop],
    verbose=0
)

y_pred = mlp_model.predict(X_test_scaled, verbose=0).flatten()
all_predictions['MLP'] = y_pred
all_models['MLP'] = mlp_model
results.append(calculate_metrics(y_test, y_pred, "MLP (Neural Network)"))

print("\n Все модели обучены!")

# Графики сравнения метрик

import pandas as pd
import matplotlib.pyplot as plt

results_df = pd.DataFrame(results)

fig, axes = plt.subplots(4, 1, figsize=(12, 24)) # Changed to 4 rows, 1 column, increased figsize

models = results_df['Model'].values

# 1. SMAPE
ax = axes[0]
colors = ['#e74c3c' for i in range(len(models))]
bars = ax.barh(models, results_df['SMAPE (%)'], color=colors)
ax.set_xlabel('SMAPE (%)', fontsize=12)
ax.set_title('Сравнение моделей по SMAPE (меньше = лучше)', fontsize=14)
ax.set_xlim(0, max(results_df['SMAPE (%)']) * 1.1) # Set x-axis limit
ax.grid(True, linestyle='--', alpha=0.7)
for i, v in enumerate(results_df['SMAPE (%)']):
    ax.text(v + 0.5, i, f'{v:.1f}%', va='center', fontsize=10)

# 2. RMSE
ax = axes[1]
bars = ax.barh(models, results_df['RMSE'], color=['#3498db' for _ in models])
ax.set_xlabel('RMSE', fontsize=12)
ax.set_title('Сравнение моделей по RMSE (меньше = лучше)', fontsize=14)
ax.set_xlim(0, max(results_df['RMSE']) * 1.1) # Set x-axis limit
ax.grid(True, linestyle='--', alpha=0.7)
for i, v in enumerate(results_df['RMSE']):
    ax.text(v + 0.5, i, f'{v:.1f}', va='center', fontsize=10)

# 3. MAE
ax = axes[2]
bars = ax.barh(models, results_df['MAE'], color=['#1abc9c' for _ in models])
ax.set_xlabel('MAE', fontsize=12)
ax.set_title('Сравнение моделей по MAE (меньше = лучше)', fontsize=14)
ax.set_xlim(0, max(results_df['MAE']) * 1.1) # Set x-axis limit
ax.grid(True, linestyle='--', alpha=0.7)
for i, v in enumerate(results_df['MAE']):
    ax.text(v + 0.5, i, f'{v:.1f}', va='center', fontsize=10)

# 4. MSE
ax = axes[3]
mse_values = results_df['MSE'].values
colors = ['#e74c3c' if v < 0 else '#2ecc71' for v in mse_values] # The condition for color is kept for consistency with original if ME was intended elsewhere but MSE is always non-negative
bars = ax.barh(models, mse_values, color=colors)
ax.axvline(x=0, color='black', linestyle='--', linewidth=1.5) # This line might be less relevant for MSE as it's non-negative, but kept as is.
ax.set_xlabel('MSE', fontsize=12)
ax.set_title('Сравнение моделей по MSE (меньше = лучше)', fontsize=14)
ax.grid(True, linestyle='--', alpha=0.7)
for i, v in enumerate(mse_values):
    ax.text(v + (0.1 if v >= 0 else -0.5), i, f'{v:.2f}', va='center', fontsize=10)

plt.tight_layout()
plt.show()

# Графики прогнозов моделей

best_model = results_df.iloc[0]['Model']
worst_model = results_df.iloc[-1]['Model']

# Create a mapping for model names to all_predictions dictionary keys
model_key_map = {
    'Ridge Regression': 'Ridge',
    'Decision Tree': 'Decision Tree',
    'Random Forest': 'Random Forest',
    'XGBoost': 'XGBoost',
    'MLP (Neural Network)': 'MLP'
}

# Get the corresponding key for all_predictions
best_model_key = model_key_map.get(best_model, best_model) # Use .get() with default to handle cases not explicitly mapped if any
worst_model_key = model_key_map.get(worst_model, worst_model)

print(f"\n Лучшая модель по SMAPE: {best_model}")
print(f" Худшая модель по SMAPE: {worst_model}")

# 1. Сравнение прогнозов лучшей модели с фактом
n_display = min(200, len(y_test))

plt.figure(figsize=(18, 8)) # Increased figsize
plt.plot(y_test[:n_display], label='Фактические продажи', linewidth=1.5, color='black', alpha=0.8)
plt.plot(all_predictions[best_model_key][:n_display], label=f'Прогноз {best_model}',
         linewidth=1.5, alpha=0.8, linestyle='--')
plt.title(f'Сравнение прогнозов {best_model} с фактическими значениями', fontsize=14)
plt.xlabel('Наблюдение', fontsize=12)
plt.ylabel('Продажи', fontsize=12)
plt.legend()
plt.grid(True, alpha=0.3)
plt.tight_layout()
plt.show()

# Сравнение всех моделей на одном графике
plt.figure(figsize=(18, 9)) # Increased figsize
plt.plot(y_test[:100], label='Факт', linewidth=2, color='black', alpha=0.9)

colors = ['#3498db', '#e67e22', '#2ecc71', '#e74c3c', '#9b59b6']
for i, (name, pred) in enumerate(all_predictions.items()):
    plt.plot(pred[:100], label=name, alpha=0.7, linestyle='--', linewidth=1.5, color=colors[i % len(colors)])

plt.title('Сравнение прогнозов всех моделей (первые 100 наблюдений)', fontsize=14)
plt.xlabel('Наблюдение', fontsize=12)
plt.ylabel('Продажи', fontsize=12)
plt.legend(loc='upper right')
plt.grid(True, alpha=0.3)
plt.tight_layout()
plt.show()

# График остатков для лучшей модели
residuals = y_test - all_predictions[best_model_key]

fig, axes = plt.subplots(1, 2, figsize=(16, 8)) # Increased figsize

# Гистограмма остатков
axes[0].hist(residuals, bins=50, edgecolor='black', alpha=0.7, color='#3498db')
axes[0].axvline(x=0, color='red', linestyle='--', linewidth=2)
axes[0].set_title(f'Распределение остатков ({best_model})', fontsize=14)
axes[0].set_xlabel('Ошибка прогноза', fontsize=12)
axes[0].set_ylabel('Частота', fontsize=12)

# График остатков по порядку
axes[1].scatter(range(len(residuals[:500])), residuals[:500], alpha=0.5, s=10, color='#3498db')
axes[1].axhline(y=0, color='red', linestyle='--', linewidth=2)
axes[1].set_title(f'Остатки по порядку наблюдений ({best_model})', fontsize=14)
axes[1].set_xlabel('Наблюдение', fontsize=12)
axes[1].set_ylabel('Остаток', fontsize=12)

plt.tight_layout()
plt.show()

# Отдельные графики для каждой модели
print("\n Сравнение прогнозов каждой модели с фактическими значениями:")
for model_name, predictions in all_predictions.items():
    # Приводим имя модели к ключу, если оно отличается
    key_name = model_key_map.get(model_name, model_name) # Используем .get() для получения ключа, если имя модели в all_predictions отличается

    plt.figure(figsize=(18, 8)) # Increased figsize
    plt.plot(y_test[:n_display], label='Фактические продажи', linewidth=1.5, color='black', alpha=0.8)
    plt.plot(predictions[:n_display], label=f'Прогноз {model_name}',
             linewidth=1.5, alpha=0.8, linestyle='--')
    plt.title(f'Сравнение прогнозов {model_name} с фактическими значениями', fontsize=14)
    plt.xlabel('Наблюдение', fontsize=12)
    plt.ylabel('Продажи', fontsize=12)
    plt.legend()
    plt.grid(True, alpha=0.3)
    plt.tight_layout()
    plt.show()

# Вывод итоговой таблицы

print("\n Итоговая таблица всех моделей:")

# Создаем копию DataFrame для форматирования. results_df содержит числовые значения.
display_df = results_df.copy()

# Начинаем стилизацию
styled_df = display_df.style

# Применение условного форматирования (меньше = лучше, поэтому highlight_min)
# Применяем к каждой числовой колонке
for col in ['SMAPE (%)', 'RMSE', 'MAE', 'MSE']:
    styled_df = styled_df.highlight_min(subset=[col], color='lightgreen')

# Функция форматирования для MSE (добавление знака + для положительных значений)
def format_mse_for_display(val):
    if pd.isna(val):
        return ''
    formatted = f"{val:.1f}".replace('.', ',')
    if val > 0:
        return f"+{formatted}"
    else:
        return formatted

# Применение форматирования для всех числовых колонок
styled_df = styled_df.format({
    'SMAPE (%)': lambda x: f"{x:.1f}".replace('.', ','),
    'RMSE': lambda x: f"{x:.1f}".replace('.', ','),
    'MAE': lambda x: f"{x:.1f}".replace('.', ','),
    'MSE': format_mse_for_display # Используем кастомную функцию
})

# Дополнительные стили для таблицы
styled_df = styled_df.set_caption("Итоговая таблица результатов моделей") \
                     .set_properties(**{'text-align': 'center', 'border': '1px solid black'}) \
                     .set_table_styles([
                         dict(selector='th', props=[('text-align', 'center'), ('background-color', '#f2f2f2')]),
                         dict(selector='td', props=[('padding', '8px')])
                     ])

# Вывод стилизованной таблицы
from IPython.display import display, HTML
display(HTML(styled_df.to_html(index=False)))

print("\n Эксперимент завершен успешно!")
