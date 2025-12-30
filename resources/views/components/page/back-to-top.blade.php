<!-- BUTTON TOP START -->
<button class="scroltop"><span class="fa fa-angle-up  relative" id="btn-vibrate"></span></button>

<!-- FLOATING CALL -->
<a href="tel:{{ preg_replace('/[^0-9]/', '', ui_value('contact-info','phone1')) }}" 
   class="position-fixed d-flex align-items-center justify-content-center" 
   style="width: 40px; height: 40px; bottom: 110px; right: 15px; z-index: 999; background: #000; color: #fff; border: none; cursor: pointer;" 
   target="_blank" 
   rel="noopener noreferrer">
    <i class="fa fa-phone"></i>
</a>

<!-- FLOATING WHATSAPP -->
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', ui_value('contact-info','whatsapp1')) }}" 
   class="position-fixed d-flex align-items-center justify-content-center" 
   style="width: 40px; height: 40px; bottom: 60px; right: 15px; z-index: 999; background: #000; color: #fff; border: none; cursor: pointer;" 
   target="_blank" 
   rel="noopener noreferrer">
    <i class="fa fa-whatsapp"></i>
</a>
