<h2>Auction House</h2>

<p>Welcome to FotheBy's Auction House!</p>
<h2>Coming Auctions: </h2>
<?php
foreach ($auctions as $l) {
    ?>
    <h3><a href="/items/<?= $l->id ?>">
            <?= $l->title ?>
        </a>
        &nbsp;&nbsp;&nbsp; On:
        <?= $l->date ?> &nbsp;&nbsp;&nbsp;At:
        <?= $l->location ?>
    </h3>
<?php } ?>