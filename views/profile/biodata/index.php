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
                  <img style="border-radius: 50%;" src="<?php echo htmlspecialchars($userData['picture'] ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg'); ?>" alt="Profile Picture">
                  <?php if ($isEditMode): ?>
                  <form action="<?= BASEURL ?>user/<?= $userData['id']; ?>/profile/update" method="POST" enctype="multipart/form-data">
                     <div class="form-group">
                        <label for="formFile">Pilih Foto</label>
                        <input class="form-control w-3" type="file" name="profile_picture" id="formFile">
                     </div>
                  </form>
                  <?php endif; ?>
               </div>
               <div class="col-md-8">
                  <?php if ($isEditMode): ?>
                  <form action="<?= BASEURL ?>user/<?= $userData['id']; ?>/profile/update" method="POST">
                     <input type="hidden" name="id_user" value="<?= htmlspecialchars($userData['id']); ?>">
                     <div class="form-group mt-1">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username..." value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                     </div>
                     <div class="form-group mt-1">
                        <label for="first_name">Nama Depan</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Masukkan nama depan..." value="<?php echo htmlspecialchars($userData['first_name']); ?>" required>
                     </div>
                     <div class="form-group mt-1">
                        <label for="last_name">Nama Belakang</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Masukkan nama belakang..." value="<?php echo htmlspecialchars($userData['last_name']); ?>" required>
                     </div>
                  
                     <h4 class="mt-4 mb-3">Kontak</h4>
                     <div class="form-group mt-1">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                     </div>
                     <div class="form-group mt-2">
                        <label for="wa_number">Nomor WhatsApp</label>
                        <input type="number" class="form-control" name="wa_number" id="wa_number" value="<?php echo htmlspecialchars($userData['wa_number']); ?>" required>
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
            


<!-- Addresses List -->
<div id="address-list">
   <?php if ($isEditMode): ?>
   <?php foreach ($addresses as $address): ?>
   <!-- Address entry for editing -->
   <div class="address-entry" id="address-<?php echo $address['id_shipping_address']; ?>">
      <h5>Alamat <?php echo $address['id_shipping_address']; ?></h5>
      <div class="address-form">
         <!-- Form fields for editing address -->
         <form method="POST" action="<?= BASEURL ?>user/<?= $address['id_user']; ?>/profile/updateAddress" data-address-id="<?php echo $address['id_shipping_address']; ?>">
            <input type="hidden" name="id_shipping_address" value="<?php echo $address['id_shipping_address']; ?>">
            <div class="form-group mt-2">
               <label for="label_name_<?php echo $address['id_shipping_address']; ?>">Label Tempat</label>
               <input type="text" class="form-control" name="label_name" id="label_name_<?php echo $address['id_shipping_address']; ?>" value="<?php echo htmlspecialchars($address['label_name']); ?>" placeholder="Label Tempat" data-address-form-field="label_name_<?php echo $address['id_shipping_address']; ?>">
            </div>
            <div class="form-group mt-2">
               <label for="street_address_<?php echo $address['id_shipping_address']; ?>">Alamat Jalan</label>
               <input type="text" class="form-control" name="street_address" id="street_address_<?php echo $address['id_shipping_address']; ?>" value="<?php echo htmlspecialchars($address['street_address']); ?>" placeholder="Alamat Jalan" data-address-form-field="street_address_<?php echo $address['id_shipping_address']; ?>">
            </div>
            <div class="form-group mt-2">
               <label for="city_<?php echo $address['id_shipping_address']; ?>">Kota</label>
               <input type="text" class="form-control" name="city" id="city_<?php echo $address['id_shipping_address']; ?>" value="<?php echo htmlspecialchars($address['city']); ?>" placeholder="Kota" data-address-form-field="city_<?php echo $address['id_shipping_address']; ?>">
            </div>
            <div class="form-group mt-2">
               <label for="state_<?php echo $address['id_shipping_address']; ?>">Provinsi</label>
               <input type="text" class="form-control" name="state" id="state_<?php echo $address['id_shipping_address']; ?>" value="<?php echo htmlspecialchars($address['state']); ?>" placeholder="Provinsi" data-address-form-field="state_<?php echo $address['id_shipping_address']; ?>">
            </div>
            <div class="form-group mt-2">
               <label for="postal_code_<?php echo $address['id_shipping_address']; ?>">Kode Pos</label>
               <input type="number" class="form-control" name="postal_code" id="postal_code_<?php echo $address['id_shipping_address']; ?>" value="<?php echo htmlspecialchars($address['postal_code']); ?>" placeholder="Kode Pos" data-address-form-field="postal_code_<?php echo $address['id_shipping_address']; ?>">
            </div>
            <div class="form-group mt-2">
               <label for="extra_note_<?php echo $address['id_shipping_address']; ?>">Catatan Tambahan</label>
               <textarea class="form-control" name="extra_note" id="extra_note_<?php echo $address['id_shipping_address']; ?>" placeholder="Catatan Tambahan" data-address-form-field="extra_note_<?php echo $address['id_shipping_address']; ?>"><?php echo htmlspecialchars($address['extra_note']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Update</button>
         </form>
      </div>
    <?php endforeach; ?>
   <?php else: ?>
      <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
         <h4>Daftar Alamat</h4>
         <div class="mb-1">
            <a href="?edit=true&tab=address" class="decoration-none">Ubah Alamat
            <i class="fa-solid fa-pen-to-square ms-2" style="color: gold;"></i>
            </a>
         </div>
      </div>
      <!-- Search Bar -->
      <div class="mb-3">
         <input type="text" id="addressSearch" class="form-control mb-3" placeholder="Cari alamat...">
      </div>
   </div>

   <!-- Display addresses when not in edit mode -->
   <?php foreach ($addresses as $address): ?>
   <div class="address-entry">
      <h5 class="mt-3">Alamat <?php echo $address['id_shipping_address']; ?></h5>
      <div class="card">
         <div class="card-body">

            <p class="mb-0"><strong>Label Tempat:</strong> <?php echo htmlspecialchars($address['label_name']); ?></p>
            <p class="mb-0"><strong>Alamat Jalan:</strong> <?php echo htmlspecialchars($address['street_address']); ?></p>
            <p class="mb-0"><strong>Kota:</strong> <?php echo htmlspecialchars($address['city']); ?></p>
            <p class="mb-0"><strong>Provinsi:</strong> <?php echo htmlspecialchars($address['state']); ?></p>
            <p class="mb-0"><strong>Kode Pos:</strong> <?php echo htmlspecialchars($address['postal_code']); ?></p>
            <p class="mb-0"><strong>Catatan Tambahan:</strong> <?php echo htmlspecialchars($address['extra_note']); ?></p>
         </div>
      </div>
   </div>
   <?php endforeach; ?>
   <?php endif; ?>
</div>


<!-- Template for adding new address form -->
<div id="new-address-form" class="mt-3 d-none">
   <div class="">
      <h5 class="pt-3 ">Tambah Alamat Baru</h5>
      <button type="button" id="remove-new-address" class="btn btn-danger">Hapus</button>
   </div>
   <form method="POST" action="<?= BASEURL?>user/<?= $address['id_user']; ?>/profile/addAddress" data-address-form="template">
      <div class="form-group mt-2">
         <label for="new_label_name">Label Tempat</label>
         <input type="text" class="form-control" name="label_name" id="new_label_name" placeholder="Label Tempat">
      </div>
      <div class="form-group mt-2">
         <label for="new_street_address">Alamat Jalan</label>
         <input type="text" class="form-control" name="street_address" id="new_street_address" placeholder="Alamat Jalan">
      </div>
      <div class="form-group mt-2">
         <label for="new_city">Kota</label>
         <input type="text" class="form-control" name="city" id="new_city" placeholder="Kota">
      </div>
      <div class="form-group mt-2">
         <label for="new_state">Provinsi</label>
         <input type="text" class="form-control" name="state" id="new_state" placeholder="Provinsi">
      </div>
      <div class="form-group mt-2">
         <label for="new_postal_code">Kode Pos</label>
         <input type="number" class="form-control" name="postal_code" id="new_postal_code" placeholder="Kode Pos">
      </div>
      <div class="form-group mt-2">
         <label for="new_extra_note">Catatan Tambahan</label>
         <textarea class="form-control" name="extra_note" id="new_extra_note" placeholder="Catatan Tambahan"></textarea>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Tambah</button>
   </form>
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
               Yakin untuk membatalkan perubahan yang telah dibuat?!
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
               <button type="button" class="btn btn-danger" onclick="clearUrlParams()">Ya, batalkan</button>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- JavaScript for Address Management -->
<?php if ($isEditMode): ?>


<script>
document.addEventListener('DOMContentLoaded', function() {
   const addButton = document.getElementById('add-address-button');
   const newAddressFormTemplate = document.getElementById('new-address-form');
   let formCount = 1; // Counter for unique form identifiers

   if (addButton) {
       addButton.addEventListener('click', function(event) {
           event.preventDefault();

           // Clone the template form
           const newForm = newAddressFormTemplate.cloneNode(true);

           // Update form attributes
           newForm.id = 'new-address-form-' + formCount;
           const form = newForm.querySelector('form');
           form.setAttribute('data-address-form', 'form-' + formCount);

           // Show the new form and add it to the DOM
           newForm.classList.remove('d-none');
           form.reset(); // Clear any pre-filled values
           
           // Add event listener to the remove button in the cloned form
           newForm.querySelector('#remove-new-address').addEventListener('click', function(event){
               newForm.remove();
           });

           document.getElementById('address-list').appendChild(newForm);

           // Scroll to the header of the new form
           const formHeader = newForm.querySelector('h5'); // Assuming the header is the first h5 element
           formHeader.scrollIntoView({ behavior: 'smooth', block: 'start' });

           formCount++;
       });
   }
});
</script>




<?php endif; ?>


<script>
   document.addEventListener('DOMContentLoaded', function() {
       const addButton = document.getElementById('add-address-button');
       const newAddressForm = document.getElementById('new-address-form');

   
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