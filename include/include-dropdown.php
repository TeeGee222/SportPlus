               <li class="line-height pt-3">
                  <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center">
                     <img src="images/user/user.png" class="img-fluid rounded-circle" alt="user">
                  </a>
                  <div class="iq-sub-dropdown iq-user-dropdown">
                     <div class="iq-card shadow-none m-0">
                        <div class="iq-card-body p-0 ">
                           <div class="bg-primary p-3">
                              <h5 class="mb-0 text-white line-height">Bonjour <?= $name ?></h5>
                              <span class="text-white font-size-12">En Ligne</span>
                           </div>
                           <a href="profil.php?id=<?=$userid?>" class="iq-sub-card iq-bg-primary-hover">
                              <div class="media align-items-center">
                                 <div class="rounded iq-card-icon iq-bg-primary">
                                    <i class="ri-file-user-line"></i>
                                 </div>
                                 <div class="media-body ml-3">
                                    <h6 class="mb-0 ">Profil</h6>
                                    <p class="mb-0 font-size-12">Voir les détails de votre profil</p>
                                 </div>
                              </div>
                           </a>
                           <a href="profil-edit.php?id=<?=$userid?>" class="iq-sub-card iq-bg-primary-hover">
                              <div class="media align-items-center">
                                 <div class="rounded iq-card-icon iq-bg-primary">
                                    <i class="ri-profile-line"></i>
                                 </div>
                                 <div class="media-body ml-3">
                                    <h6 class="mb-0 ">Modifier votre Profil</h6>
                                    <p class="mb-0 font-size-12">Modifier vos informations personnelles</p>
                                 </div>
                              </div>
                           </a>
                           <a href="<?=$groupe_href?>" class="iq-sub-card iq-bg-primary-hover">
                              <div class="media align-items-center">
                                 <div class="rounded iq-card-icon iq-bg-primary">
                                    <i class="ri-account-box-line"></i>
                                 </div>
                                 <div class="media-body ml-3">
                                    <h6 class="mb-0 "><?=$groupe_display?></h6>
                                    <p class="mb-0 font-size-12"><?=$groupe_display_under?></p>
                                 </div>
                              </div>
                           </a>
                           <a href="amis.php?id=<?=$userid?>" class="iq-sub-card iq-bg-primary-hover">
                              <div class="media align-items-center">
                                 <div class="rounded iq-card-icon iq-bg-primary">
                                    <i class="ri-open-arm-line"></i>
                                 </div>
                                 <div class="media-body ml-3">
                                    <h6 class="mb-0 ">Amis</h6>
                                    <p class="mb-0 font-size-12">Gérer votre liste d'amis</p>
                                 </div>
                              </div>
                           </a>
                           <div class="d-inline-block w-100 text-center p-3">
                              <a class="bg-primary iq-sign-btn" href="deconnexion.php" role="button">Déconnexion<i class="ri-login-box-line ml-2"></i></a>
                           </div>
                        </div>
                     </div>
                  </div>
               </li>
            </ul>
         </div>
      </nav>
   </div>
</div>