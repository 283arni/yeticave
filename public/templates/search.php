
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
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $text; ?></span>»</h2>
        <?php if(!empty($cards)): ?>
          <ul class="lots__list">
                <?php foreach ($cards as $card): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="uploads/<?= $card["image_lot"] ?>" width="350" height="260" alt="<?= htmlspecialchars($card["name_lot"]) ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($card['name_cat']) ?></span>
                            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $card["id"] ?>"><?= htmlspecialchars($card["name_lot"]) ?></a></h3>
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
        </ul>
        <?php else: ?>
            <p>Ничего не найдено</p>
        <?php endif; ?>
      </section>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
      </ul>
    </div>
  </main>

