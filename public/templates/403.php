<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="all-lots.php?category=<?= $category["code_cat"] ?>"><?= $category["name_cat"] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2>Ошибка 403 (Forbidden, Доступ запрещён) </h2>
        <p>Авторизуйтесь на сайте.</p>
    </section>
</main>
