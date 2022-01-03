<div class="card-carousel">  
    <div class="my-card prev">
        <div class="card" style="width: 18rem;">
        <img src="images/exercices/<?=$exercice_illustration?>" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?=$exercice_en_cours?> <?php if($exercice_repetition_ou_timer == 1){echo "<span class='badge badge-primary'>X$param_exo_now_repet</span>";}?></h5>
                <a data-toggle="modal" data-target="#exo-info" href="" class="text-primary"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
    <div class="my-card active">
    <div class="card" style="width: 18rem;">
    <img src="images/exercices/<?=$exercice_illustration?>" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title"><?=$exercice_en_cours?> <?php if($exercice_repetition_ou_timer == 1){echo "<span class='badge badge-primary'>X$param_exo_now_repet</span>";}?></h5>
            <h2><a data-toggle="modal" data-target="#exo-info" href="" class="text-primary"><i class="fa fa-info-circle" aria-hidden="true"></i></a></h2>
        </div>
    </div>
</div>

<div class="my-card prev">
    <div class="card" style="width: 18rem;">
    <img src="images/exercices/<?=$exercice_illustration?>" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title"><?=$exercice_en_cours?> <?php if($exercice_repetition_ou_timer == 1){echo "<span class='badge badge-primary'>X$param_exo_now_repet</span>";}?></h5>
            <a data-toggle="modal" data-target="#exo-info" href="" class="text-primary"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
</div>











































