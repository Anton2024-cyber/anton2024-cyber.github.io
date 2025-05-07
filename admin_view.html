<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title>Admin Panel - User Management</title>
<style>
  /* Reset and base */
  * {
    box-sizing: border-box;
  }
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9f9f9;
    margin: 0;
    padding: 20px;
    color: #333;
  }
  h1, h2 {
    color: #222;
  }

  .container {
    max-width: 900px;
    margin: 0 auto;
  }

  /* Messages */
  .messages {
    margin-bottom: 20px;
    padding: 10px;
    background: #e0ffe0;
    border: 1px solid #6ac46a;
    border-radius: 4px;
    color: #2a7a2a;
  }

  /* Table styles */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
  }
  th, td {
    padding: 8px 10px;
    border: 1px solid #ddd;
    vertical-align: top;
  }
  th {
    background-color: #4a90e2;
    color: white;
    text-align: left;
  }
  tr:nth-child(even) {
    background-color: #f0f6ff;
  }
  tr:hover {
    background-color: #dbe9ff;
  }

  /* Form styles */
  form.edit-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }
  form.edit-form > div.field {
    flex: 1 1 45%;
    display: flex;
    flex-direction: column;
  }
  label {
    margin-bottom: 3px;
    font-weight: 600;
  }
  input[type="text"],
  input[type="email"],
  input[type="tel"],
  input[type="date"],
  textarea {
    padding: 6px 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical;
  }
  textarea {
    min-height: 60px;
  }
  .radio-group {
    display: flex;
    gap: 10px;
    align-items: center;
  }
  
  .checkbox-group {
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .languages-group {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }
  .languages-group label {
    font-weight: normal;
  }

  .btn {
    background: #4a90e2;
    border: none;
    color: white;
    padding: 7px 14px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
  }
  .btn:hover, .btn:focus {
    background: #357abd;
  }
  .btn-danger {
    background: #e04a4a;
  }
  .btn-danger:hover, .btn-danger:focus {
    background: #b13030;
  }

  /* Responsive */
  @media (max-width: 600px) {
    form.edit-form > div.field {
      flex: 1 1 100%;
    }
    table, thead, tbody, th, td, tr {
      display: block;
    }
    thead tr {
      display: none;
    }
    tr {
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 6px;
      padding: 10px;
      background: white;
    }
    td {
      border: none;
      padding-left: 50%;
      position: relative;
      white-space: normal;
    }
    td::before {
      content: attr(data-label);
      position: absolute;
      left: 10px;
      font-weight: 700;
      white-space: nowrap;
    }
  }

  /* Statistics styles */
  .stats {
    margin-bottom: 40px;
    padding: 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 6px rgba(0,0,0,0.1);
  }
  .stats h2 {
    margin-top: 0;
  }
  .stats ul {
    list-style: none;
    padding: 0;
  }
  .stats ul li {
    margin-bottom: 6px;
  }
</style>
</head>
<body>
<div class="container">

  <h1>Admin Panel - User Management</h1>

  <?php if (!empty($messages)): ?>
    <div class="messages">
      <?php foreach ($messages as $msg) {
        echo htmlspecialchars($msg) . "<br>";
      } ?>
    </div>
  <?php endif; ?>

  <section class="stats">
    <h2>Programming Languages Popularity</h2>
    <ul>
      <?php foreach ($lang_stats as $lang => $count): ?>
        <li><strong><?=htmlspecialchars($lang)?></strong>: <?=intval($count)?></li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total Users:</strong> <?=count($users)?></p>
  </section>

  <section class="users">
    <h2>Users</h2>
    <?php if (empty($users)): ?>
      <p>No users found.</p>
    <?php else: ?>
      <table aria-label="User data table" role="grid">
        <thead>
          <tr>
            <th>#</th>
            <th>Имя</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Дата рождения</th>
            <th>Пол</th>
            <th>Биография</th>
            <th>Любимые ЯП</th>
            <th>Согласие</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $id => $user): ?>
          <tr>
            <td data-label="#"> <?= $id ?> </td>
            <td data-label="Имя"><?= $user['name'] ?></td>
            <td data-label="Телефон"><?= $user['phone'] ?></td>
            <td data-label="Email"><?= $user['email'] ?></td>
            <td data-label="Дата рождения"><?= $user['birthday'] ?></td>
            <td data-label="Пол"><?= ($user['gender'] === 'male') ? 'Мужской' : (($user['gender'] === 'female') ? 'Женский' : '') ?></td>
            <td data-label="Биография"><?= nl2br($user['bio']) ?></td>
            <td data-label="Любимые ЯП"><?= htmlspecialchars(implode(', ', $user['languages'])) ?></td>
            <td data-label="Согласие"><?= $user['agreement'] ? 'Да' : 'Нет' ?></td>
            <td data-label="Действия" style="min-width: 200px;">

              <details>
                <summary style="cursor:pointer; outline:none;">Редактировать</summary>
                <form class="edit-form" method="post" action="admin.php" novalidate>
                  <input type="hidden" name="user_id" value="<?= $id ?>">
                  <input type="hidden" name="edit" value="1">

                  <div class="field">
                    <label for="name_<?= $id ?>">Имя</label>
                    <input type="text" id="name_<?= $id ?>" name="name" value="<?= $user['name'] ?>" required>
                  </div>

                  <div class="field">
                    <label for="phone_<?= $id ?>">Телефон</label>
                    <input type="tel" id="phone_<?= $id ?>" name="phone" value="<?= $user['phone'] ?>">
                  </div>

                  <div class="field">
                    <label for="email_<?= $id ?>">Email</label>
                    <input type="email" id="email_<?= $id ?>" name="email" value="<?= $user['email'] ?>" required>
                  </div>

                  <div class="field">
                    <label for="birthday_<?= $id ?>">Дата рождения</label>
                    <input type="date" id="birthday_<?= $id ?>" name="birthday" value="<?= $user['birthday'] ?>">
                  </div>

                  <div class="field">
                    <label>Пол</label>
                    <div class="radio-group">
                      <label><input type="radio" name="gender" value="male" <?= ($user['gender']==='male') ? 'checked' : '' ?>> Мужской</label>
                      <label><input type="radio" name="gender" value="female" <?= ($user['gender']==='female') ? 'checked' : '' ?>> Женский</label>
                    </div>
                  </div>

                  <div class="field" style="flex: 1 1 100%;">
                    <label for="bio_<?= $id ?>">Биография</label>
                    <textarea id="bio_<?= $id ?>" name="bio"><?= $user['bio'] ?></textarea>
                  </div>

                  <div class="field" style="flex: 1 1 100%;">
                    <label>Любимые Язык программирования</label>
                    <div class="languages-group">
                    <?php foreach ($lang_stats as $lang => $_): ?>
                      <label>
                        <input type="checkbox" name="languages[]" value="<?= htmlspecialchars($lang) ?>" <?= in_array($lang, $user['languages']) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($lang) ?>
                      </label>
                    <?php endforeach; ?>
                    </div>
                  </div>

                  <div class="field checkbox-group" style="flex: 1 1 100%;">
                    <label>
                      <input type="checkbox" name="agreement" <?= $user['agreement'] ? 'checked' : '' ?>>
                      Согласие с договором
                    </label>
                  </div>

                  <div class="field" style="flex: 1 1 100%;">
                    <button class="btn" type="submit">Сохранить</button>
                  </div>
                </form>
              </details>

              <form method="post" action="admin.php" onsubmit="return confirm('Удалить пользователя #<?= $id ?>?');" style="margin-top: 8px;">
                <input type="hidden" name="user_id" value="<?= $id ?>">
                <input type="hidden" name="delete" value="1">
                <button class="btn btn-danger" type="submit">Удалить</button>
              </form>

            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>

</div>
</body>
</html>
</content>
</create_file>
