<?php
$id = $_SESSION["user"]["id"];
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
    <section class="rates container">
        <h2>Мои ставки</h2>
        <?php if (!empty($bets)): ?>
            <table class="rates__list">
                <?php foreach ($bets as $bet): ?>

                    <tr class="rates__item">
                        <?php $classname = ($bet["winner_id"] == $id) ? "rates__item--win" : ""; ?>
                        <td class="rates__info <?= $classname ?>">
                            <div class="rates__img">
                                <img src="uploads/<?= $bet["image_lot"] ?>" width="54" height="40"
                                     alt="<?= $bet["name_lot"] ?>">
                            </div>
                            <?php if ($bet["winner_id"] == $id) : ?>
                                <div>
                                    <h3 class="rates__title"><a
                                            href="lot.php?id=<?= $bet["id"] ?>"><?= $bet["name_lot"] ?></a></h3>
                                    <p><?= $bet["contact"] ?></p>
                                </div>
                            <?php else: ?>
                                <h3 class="rates__title"><a
                                        href="lot.php?id=<?= $bet["id"] ?>"><?= $bet["name_lot"] ?></a></h3>
                            <?php endif; ?>
                        </td>
                        <td class="rates__category">
                            <?= $bet["name_cat"] ?>
                        </td>
                        <td class="rates__timer">
                            <?php if ($bet["winner_id"] == $id) : ?>
                                <div class="timer timer--win">Ставка выиграла</div>
                            <?php else : ?>
                                <?php $res = set_time_lot(htmlspecialchars($bet["dt_end"])) ?>
                                <div class="timer <?= (int)$res[0] < 1 ? "timer--finishing" : '' ?>">
                                    <?= "$res[0] : $res[1]" ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="rates__price">
                            <?= $bet["price"] ?> р
                        </td>
                        <td class="rates__time">
                            <?= format_time($bet["dt_add"]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <!--                <tr class="rates__item rates__item--end">-->
                <!--                  <td class="rates__info">-->
                <!--                    <div class="rates__img">-->
                <!--                      <img src="../img/rate7.jpg" width="54" height="40" alt="Сноуборд">-->
                <!--                    </div>-->
                <!--                    <h3 class="rates__title"><a href="lot.html">DC Ply Mens 2016/2017 Snowboard</a></h3>-->
                <!--                  </td>-->
                <!--                  <td class="rates__category">-->
                <!--                    Доски и лыжи-->
                <!--                  </td>-->
                <!--                  <td class="rates__timer">-->
                <!--                    <div class="timer timer--end">Торги окончены</div>-->
                <!--                  </td>-->
                <!--                  <td class="rates__price">-->
                <!--                    10 999 р-->
                <!--                  </td>-->
                <!--                  <td class="rates__time">-->
                <!--                    19.03.17 в 08:21-->
                <!--                  </td>-->
                <!--                </tr>-->
            </table>
        <?php else: ?>
            <p>У вас еще нет ставок</p>
        <?php endif; ?>
    </section>
</main>
