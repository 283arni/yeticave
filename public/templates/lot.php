
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
        <h2><?= $card["name_lot"]?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="../uploads/<?= $card["image_lot"]?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $card["name_cat"]?></span></p>
                <p class="lot-item__description"><?= $card["desc_lot"]?></p>
            </div>
            <div class="lot-item__right">
                <?php if (isset($_SESSION["user"])): ?>
                    <div class="lot-item__state">
                        <?php $res = set_time_lot(htmlspecialchars($card["dt_end"])) ?>
                        <div class="lot-item__timer timer <?= (int) $res[0] < 1 ? "timer--finishing" : '' ?>">
                            <?= "$res[0] : $res[1]"?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= $card["price"]?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= $card['step']?> р</span>
                            </div>
                        </div>
                        <form class="lot-item__form" action="lot.php?id=<?= $_GET["id"] ?>" method="post" autocomplete="off">
                            <?php $classname = (!empty($errors)) ? "form__item--invalid" : ''; ?>
                            <p class="lot-item__form-item form__item <?= $classname ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder="<?= $card['step'] ?>">
                                <span class="form__error"><?= $errors["cost"] ?? ''?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <?php endif; ?>
                <div class="history">
                    <h3>История ставок (<span>10</span>)</h3>
                    <table class="history__list">
                        <tr class="history__item">
                            <td class="history__name">Иван</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">5 минут назад</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Константин</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">20 минут назад</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Евгений</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">Час назад</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Игорь</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 08:21</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Енакентий</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 13:20</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Семён</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 12:20</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Илья</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 10:20</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Енакентий</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 13:20</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Семён</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 12:20</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Илья</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 10:20</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
