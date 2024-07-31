<?php
   requireView("partials/head.php");
   requireView("partials/navbar.php");
   ?> 
<section class="content">
   <div class="container mt-5">
      <div class="profile-header d-flex justify-content-between align-items-center mb-2">
         <div class="d-flex align-items-center">
            <h4 class="me-2 ms-1"><i class="fa-solid fa-user"></i></h4>
            <h4 class="ml-3">Profile</h4>
         </div>
      </div>
      <!-- Profile Nav -->
      <ul class="nav nav-tabs" id="profileTab" role="tablist">
         <li class="nav-item">
            <a class="nav-link active decoration-none" id="biodata-tab" data-bs-toggle="tab" href="#biodata" role="tab" aria-controls="biodata" aria-selected="true">Biodata Diri</a>
         </li>
         <li class="nav-item">
            <a class="nav-link decoration-none" id="address-tab" data-bs-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false">Daftar Alamat</a>
         </li>
      </ul>
      <div class="tab-content mt-3" id="profileTabContent">
         <!-- Biodata Tab -->
         <div class="tab-pane fade show active" id="biodata" role="tabpanel" aria-labelledby="biodata-tab">
            <div class="row">
               <div class="col-md-4 text-center">
                  <img style="border-radius: 50%;" src="<?php echo htmlspecialchars($userData['profile_picture'] ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg'); ?>" alt="Profile Picture">
                  <?php if ($isEditMode): ?>
                  <form>
                     <div class="form-group">
                        <label for="formFile">Pilih Foto</label>
                        <input class="form-control w-3" type="file" id="formFile">
                     </div>
                  </form>
                  <?php endif; ?>
               </div>
               <div class="col-md-8">
                  <?php if ($isEditMode): ?>
                  <form>
                     <div class="form-group mt-1">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Masukkan username..." value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                     </div>
                     <div class="form-group mt-1">
                        <label for="first_name">Nama Depan</label>
                        <input type="text" class="form-control" id="first_name" placeholder="Masukkan nama depan..." value="<?php echo htmlspecialchars($userData['first_name']); ?>" required>
                     </div>
                     <div class="form-group mt-1">
                        <label for="last_name">Nama Belakang</label>
                        <input type="text" class="form-control" id="last_name" placeholder="Masukkan nama belakang..." value="<?php echo htmlspecialchars($userData['last_name']); ?>" required>
                     </div>
                  </form>
                  <h4 class="mt-4 mb-3">Kontak</h4>
                  <form>
                     <div class="form-group mt-1">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                     </div>
                     <div class="form-group mt-2">
                        <label for="wa_number">Nomor WhatsApp</label>
                        <input type="text" class="form-control" id="wa_number" value="<?php echo htmlspecialchars($userData['wa_number']); ?>" required>
                     </div>
                     <div class="d-flex justify-content-end flex-wrap">
                        <a href="#" class="btn btn-danger mt-3 mb-3 me-2" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        Batal Perubahan
                        </a>                                    
                        <button class="btn btn-success mt-3 mb-3">Simpan</button>
                     </div>
                  </form>
                  <?php else: ?>
                  <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                     <h4>Biodata Diri</h4>
                     <div class="mb-1">
                        <a href="?edit=true&tab=biodata" class="decoration-none">Ubah Biodata
                        <i class="fa-solid fa-pen-to-square ms-2" style="color: gold;"></i>
                        </a>
                     </div>
                  </div>
                  <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
                  <p><strong>Nama Depan:</strong> <?php echo htmlspecialchars($userData['first_name']); ?></p>
                  <p><strong>Nama Belakang:</strong> <?php echo htmlspecialchars($userData['last_name']); ?></p>
                  <h4 class="mt-4 mb-3">Kontak</h4>
                  <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
                  <p><strong>Nomor WhatsApp:</strong> <?php echo htmlspecialchars($userData['wa_number']); ?></p>
                  <?php endif; ?>
               </div>
            </div>
         </div>
         <!-- Address Tab -->
         <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
               <h4>Daftar Alamat</h4>
               <?php if ($isEditMode): ?>
               <div class="mb-1">
                  <div class="d-flex justify-content-end flex-wrap">
                     <a href="#" class="btn btn-primary mt-3 mb-3 me-2" id="add-address-button">
                     Tambah Alamat
                     <i class="fa-solid fa-plus ms-2" style="color: white;"></i>
                     </a>
                     <a href="#" class="btn btn-danger mt-3 mb-3 me-2" data-bs-toggle="modal" data-bs-target="#cancelModal">
                     Batal Perubahan
                     </a>
                     <button class="btn btn-success mt-3 mb-3">Simpan</button>
                  </div>
               </div>
               <?php endif; ?>
            </div>
            <!-- Search Bar -->
            <div class="mb-3">
               <input type="text" id="addressSearch" class="form-control" placeholder="Cari alamat...">
            </div>

         </div>
      </div>
   </div>
   <!-- Modal -->
   <div class="modal fade animate-1sec slineId" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="cancelModalLabel">Peringatan</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               Yakin untuk membuang perubahan yang telah dibuat?!
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
               <button type="button" class="btn btn-danger" onclick="clearUrlParams()">Ya, hapus</button>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- JavaScript for Address Management -->
<script>
   document.addEventListener('DOMContentLoaded', function() {
       const addButton = document.getElementById('add-address-button');
       const newAddressForm = document.getElementById('new-address-form');
   
       if (addButton) {
           addButton.addEventListener('click', function(event) {
               event.preventDefault();
               newAddressForm.classList.toggle('d-none');
               if (!newAddressForm.classList.contains('d-none')) {
                   newAddressForm.scrollIntoView({ behavior: 'smooth' });
               }
           });
       }
   
       const searchInput = document.getElementById('addressSearch');
       const addressEntries = document.querySelectorAll('.address-entry');
   
       if (searchInput) {
           searchInput.addEventListener('input', function() {
               const searchValue = searchInput.value.toLowerCase();
               addressEntries.forEach(entry => {
                   const entryText = entry.textContent.toLowerCase();
                   entry.style.display = entryText.includes(searchValue) ? '' : 'none';
               });
           });
       }
   });
</script>
<script>
   function clearUrlParams() {
       // Remove all URL parameters and refresh the page
       const baseUrl = window.location.origin + window.location.pathname;
       window.location.href = baseUrl;
   }
</script>
<!-- JavaScript for Tab State -->
<script>
   document.addEventListener('DOMContentLoaded', function() {
       // Function to activate a tab
       function activateTab(tabId) {
           const tabLinks = document.querySelectorAll('#profileTab .nav-link');
           const tabPanes = document.querySelectorAll('.tab-pane');
           tabLinks.forEach(link => link.classList.remove('active'));
           tabPanes.forEach(pane => pane.classList.remove('show', 'active'));
   
           document.getElementById(tabId + '-tab').classList.add('active');
           document.getElementById(tabId).classList.add('show', 'active');
       }
   
       // Get current tab from URL hash
       const urlParams = new URLSearchParams(window.location.search);
       const currentTab = urlParams.get('tab') || 'biodata';
       activateTab(currentTab);
   
       // Add event listeners to edit buttons
       document.querySelectorAll('.edit-button').forEach(button => {
           button.addEventListener('click', function(event) {
               event.preventDefault();
               const tabId = this.getAttribute('data-tab');
               window.location.search = `?edit=true&tab=${tabId}`;
           });
       });
   });
</script>
<?php
   requireView("partials/footer.php");
   ?>