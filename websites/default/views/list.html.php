<h1>
    <?= $data_title ?>
</h1>

<?php if (isset($add)) { ?>
    <a href="<?= $add ?>" style="width: fit-content; margin-left:auto; margin-right:auto;"> Add </a>
<?php } ?>
<table>
    <tr>
        <?php
        foreach ($headers as $h) {
            ?>
            <td>
                <?= $h ?>
            </td>
        <?php } ?>
    </tr>
    <hr />
    <tbody>
        <?php
        foreach ($data as $d) {
            echo "<tr>";
            foreach ($cors as $c) {
                ?>
                <td>
                    <?= $d->$c ?>
                </td>

                <?php
            }
            foreach ($actions as $a) {
                echo '<td> <a  class="btn" href="' . $a[0] . '/' . $d->id . '">' . $a[1] . ' </a></td>';
            }
            echo "</tr>";
        }

        ?>
    </tbody>
</table>