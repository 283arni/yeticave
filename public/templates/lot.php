<?php
$res = set_time_lot($card["dt_end"]);
$is_current_user = $res[1] > 0 && isset($_SESSION["user"]) && $_SESSION["user"]["id"] != $card["author_id"];
?>
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
        <h2><?= $card["name_lot"] ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="../uploads/<?= $card["image_lot"] ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $card["name_cat"] ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($card["desc_lot"]) ?></p>
            </div>
            <div class="lot-item__right">
                <?php if ($is_current_user): ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer <?= (int)$res[0] < 1 ? "timer--finishing" : '' ?>">
                            <?= "$res[0] : $res[1]" ?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= htmlspecialchars($card["price"]) ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= htmlspecialchars($card['step']) ?> р</span>
                            </div>
                        </div>
                        <form class="lot-item__form" action="lot.php?id=<?= htmlspecialchars($_GET["id"]) ?>"
                              method="post" autocomplete="off">
                            <?php $classname = (!empty($errors)) ? "form__item--invalid" : ''; ?>
                            <p class="lot-item__form-item form__item <?= $classname ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost"
                                       placeholder="<?= htmlspecialchars($card['step']) ?>">
                                <span class="form__error"><?= $errors["cost"] ?? '' ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <?php endif; ?>
                <?php if (!empty($bets)): ?>
                    <div class="history">
                        <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($bets as $bet): ?>
                                <tr class="history__item">
                                    <td class="history__name"><?= $bet["name_user"] ?></td>
                                    <td class="history__price"><?= $bet["price"] ?> р</td>
                                    <td class="history__time"><?= format_time($bet["dt_add"]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
