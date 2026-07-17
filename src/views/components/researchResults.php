

<?php if (isset($this->result["tournaments"])): ?>
    <h3>Tournois trouvés</h3>
    <?php foreach ($this->result["tournaments"] as $t): ?>
        <div class="research-box">
            <h4><?= $t["club"] . ", " . $t["city"] ?> <em>(<?= $t["name"] ?>)</em></h4>
            <p>Du <?= dateFormatter()->format($t["start_time"]) ?> au <?= dateFormatter()->format($t["end_time"]) ?></p>
            <p>Directeur : <?= $t["director_name"] ?>. <a href="mailto:<?= $t["director_email"] ?>"><?= $t["director_email"] ?></a></p>
            <a href="./espace-admin/details?tournament=<?= $t["id"] ?>" class="button">Détail</a>
        </div>
<?php endforeach; endif; ?>
<?php if (isset($this->result["directors"])): ?>
    <h3>Directeurs trouvés</h3>
    <?php foreach ($this->result["directors"] as $d): ?>
        <div class="research-box">
            <h4><?= $d["name"] ?></h4>
            <p>Email : <?= $d["email"] ?></p>
            <p>Ce compte est <?= $d["is_permanent"] === 1 ? "permanent" : "temporaire"; ?>.
             Il est actuellement <?= $d["is_suspended"] === 1 ? "suspendu" : "actif"; ?>.</p>
             <a href="./espace-admin/details?director=<?= $t["id"] ?>" class="button">Détail</a>
        </div>
<?php endforeach; endif; ?>