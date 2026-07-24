
<div class="element-courts">
    <?php foreach ($t["courts"] as $c): ?>
        <div class="courts">
            <h4><?= $c["name"] ?></h4>
            <div class="matches">
                <?php foreach ($c["matches"] as $m): ?>


                    <div class="one-match">
                        <div class="names name-1">
                            <?= isset($m["teamAName"]) ? $m["teamAName"] : "" ?>
                        </div>
                        <div class="points P1-score points">
                            <?=  isset($m["teamA_points"]) ? $m["teamA_points"] : "" ?>
                        </div>
                        <div class="service P1-score serve" isServer="<?= (isset($m["service"]) && ($m["service"] === "TAP1" || $m["service"] === "TAP2" || $m["service"] === null)) ? "true" : "false" ?>">
                            
                        </div>
                        <div class="set1 P1-score set1">
                            <?=  isset($m["teamA_set1"]) ? $m["teamA_set1"] : "" ?>
                        </div>
                        <div class="set2 P1-score set2">
                            <?=  isset($m["teamA_set2"]) ? $m["teamA_set2"] : "" ?>
                        </div>
                        <div class="set3 P1-score set3">
                            <?=  isset($m["teamA_set3"]) ? $m["teamA_set3"] : "" ?>
                        </div>
                        <div class="points P2-score points">
                            <?=  isset($m["teamB_points"]) ? $m["teamB_points"] : "" ?>
                        </div>
                        <div class="service P2-score serve" isServer="<?= (isset($m["service"]) && ($m["service"] === "TBP1" || $m["service"] === "TBP2")) ? "true" : "false" ?>">
                        </div>
                        <div class="set1 P2-score set1">
                            <?=  isset($m["teamB_set1"]) ? $m["teamB_set1"] : "" ?>
                        </div>
                        <div class="set2 P2-score set2">
                            <?=  isset($m["teamB_set2"]) ? $m["teamB_set3"] : "" ?>
                        </div>
                        <div class="set3 P2-score set3">
                            <?=  isset($m["teamB_set3"]) ? $m["teamB_set3"] : "" ?>
                        </div>
                        <div class="names name-2">
                            <?= isset($m["teamBName"]) ? $m["teamBName"] : "" ?>
                        </div>
                        <div class="match-status">
                            <?= isset($m["status"]) ? $m["status"] : "" ?>
                        </div>
                        <div class="match-time">
                            <?= isset($m["match_time"]) ? $m["match_time"] : "" ?>
                        </div>
                    </div>


                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<div class="element-players">

</div>