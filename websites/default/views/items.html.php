<h1> Items for
    '
    <?= $lot->title ?>'
</h1>
<p>
    <strong>Auction Date:</strong>
    <?= isset($lot->date) ? htmlspecialchars($lot->date) : '' ?>
</p>
<p>
    <strong>Auction period:</strong>
    <?= $lot->period ?>
</p>

<?php foreach ($auctions as $r) { ?>
    <p>
    <h3>
        <a href="/catalogue/<?= $r->id ?>">
            <?= $r->title ?>
        </a>
    </h3>
    <?= $r->description ?>
    </p>
    <hr>
<?php }