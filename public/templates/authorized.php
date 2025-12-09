<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach($categories as $category): ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= $category["name_cat"] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2>Вы уже авторизованы</h2>
        <p>Страница только для не авторизованных посетителей.</p>
    </section>
</main>
