<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
            снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach ($categories as $category): ?>
                <li class="promo__item promo__item--<?= $category['code_cat'] ?>">
                    <a class="promo__link"
                       href="all-lots.php?category=<?= $category["code_cat"] ?>"><?= $category["name_cat"] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots" id="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <?php if (!empty($cards)): ?>
            <ul class="lots__list">
                <!--заполните этот список из массива с товарами-->
                <?php foreach ($cards as $card): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="uploads/<?= $card["image_lot"] ?>" width="350" height="260"
                                 alt="<?= htmlspecialchars($card["name_lot"]) ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($card['name_cat']) ?></span>
                            <h3 class="lot__title"><a class="text-link"
                                                      href="lot.php?id=<?= $card["id"] ?>"><?= htmlspecialchars($card["name_lot"]) ?></a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span
                                        class="lot__cost"><?= htmlspecialchars(set_price($card["price"])) . " ₽" ?></span>
                                </div>

                                <?php $res = set_time_lot($card["dt_end"]) ?>
                                <div class="lot__timer timer <?= (int)$res[0] < 1 ? "timer--finishing" : '' ?>">
                                    <?= "$res[0] : $res[1]" ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Нет доступных лотов</p>
        <?php endif; ?>
    </section>
    <?php if ($totalPages > 1): ?>
        <ul class="pagination-list">

            <!-- Назад -->
            <li class="pagination-item pagination-item-prev <?= $page <= 1 ? 'pagination-item-disabled' : '' ?>">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>#lots">Назад</a>
                <?php else: ?>
                    <a>Назад</a>
                <?php endif; ?>
            </li>

            <!-- Страницы -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="pagination-item <?= $i === $page ? 'pagination-item-active' : '' ?>">
                    <?php if ($i === $page): ?>
                        <a><?= $i ?></a>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>#lots">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endfor; ?>

            <!-- Вперёд -->
            <li class="pagination-item pagination-item-next <?= $page >= $totalPages ? 'pagination-item-disabled' : '' ?>">
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>#lots">Вперёд</a>
                <?php else: ?>
                    <a>Вперёд</a>
                <?php endif; ?>
            </li>

        </ul>
    <?php endif; ?>
</main>
