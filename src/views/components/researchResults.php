

<?php if (isset($this->result["tournaments"])): ?>
    <h3>Tournois trouvés</h3>
    <?php foreach ($this->result["tournaments"] as $t): ?>
        <pre><?php // print_r($t) ?></pre>
        <div class="research-box">
            <h4><?= $t["club"] . ", " . $t["city"] ?> <em>(<?= $t["name"] ?>)</em></h4>
            <p>Du <?= dateFormatter()->format($t["start_time"]) ?> au <?= dateFormatter()->format($t["end_time"]) ?></p>
            <p>Directeur : <?= $t["director_name"] ?>. <a href="mailto:<?= $t["director_email"] ?>"><?= $t["director_email"] ?></a></p>
            <a href="./details?tournament=<?= $t["id_to_display"] ?>" class="button">Détail</a>
            <a href="./edit?tournament=<?= $t["id_to_display"] ?>" class="button">Éditer</a>
        </div>
<?php endforeach; endif; ?>
<?php if (isset($this->result["directors"])): ?>
    <h3>Directeurs trouvés</h3>
    <?php foreach ($this->result["directors"] as $d): ?>
        <div class="research-box">
            <h4><?= $d["name"] ?></h4>
            <p>Email : <a href="mailto:<?= $d["email"] ?>"><?= $d["email"] ?></a></p>
            <p>Compte permanent : <?= $d["is_permanent"] === 1 ? "☑" : "☒"; ?></p>
            <p>Compte suspendu : <?= $d["is_suspended"] === 1 ? "☑" : "☒"; ?></p>
            <button class="edit-director button" 
            director-id="<?= $d["id_to_display"] ?>" director-name="<?= $d["name"] ?>"
            director-email="<?= $d["email"] ?>" director-role="<?= $d["role"] ?>"
            director-permanent="<?= $d["is_permanent"] ?>" director-suspended="<?= $d["is_suspended"] ?>"
            >Éditer</button>
        </div>
<?php endforeach; endif; ?>