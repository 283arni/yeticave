    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach ($categories as $category): ?>
                <li class="promo__item promo__item--<?= $category['code_cat'] ?>">
                    <a class="promo__link" href="pages/all-lots.html"><?= $category["name_cat"] ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php if(!empty($cards)): ?>
                <?php foreach ($cards as $card): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= $card["image_lot"] ?>" width="350" height="260" alt="<?= htmlspecialchars($card["name_lot"]) ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($card['name_cat']) ?></span>
                            <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= htmlspecialchars($card["name_lot"]) ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= htmlspecialchars(set_price($card["price"])) . " ₽" ?></span>
                                </div>

                                <?php $res = set_time_lot(htmlspecialchars($card["dt_end"])) ?>
                                <div class="lot__timer timer <?= (int) $res[0] < 1 ? "timer--finishing" : '' ?>">
                                    <?= "$res[0] : $res[1]"?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
