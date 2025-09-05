<footer class="beer-footer mt-auto pt-5">
  <div class="container-xxl">

    <div class="row g-4 align-items-start pb-4">
      <div class="col-md-5">
        <div class="d-flex align-items-center mb-3">
          <img src="{{ asset('images/logo.WEBP') }}" alt="ES Pivara" style="height:72px" class="me-2">
          <h5 class="mb-0" style="font-weight:800; letter-spacing:.80px">ES Pivara</h5>
        </div>
        <p class="mb-3" style="max-width:520px; font-size:1.02rem; line-height:1.5">
          U ES Pivari verujemo da je svako pivo priča – o žitu, hmelju, vodi i strasti.
          Na našim degustacijama povezujemo ljude i ukuse: od klasika do
          eksperimentalnih serija, uz stručne vodiče i prijatnu atmosferu.
        </p>
        <div class="beer-social mb-3">
          <a href="#" aria-label="Instagram"><i class="icon ion-logo-instagram"></i></a>
          <a href="#" aria-label="Facebook"><i class="icon ion-logo-facebook"></i></a>
          <a href="#" aria-label="Twitter"><i class="icon ion-logo-twitter"></i></a>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <h6 class="mb-2" style="font-weight:800">Brzi linkovi</h6>
        <ul class="list-unstyled">
          <li><a href="{{ route('degustacijas.index') }}">Degustacije</a></li>
          @can('client') <li><a href="{{ route('prIjavas.index') }}">Moje prijave</a></li> @endcan
          @can('managerOrAdmin') <li><a href="{{ route('degustacijas.create') }}">Nova degustacija</a></li> @endcan
          @can('admin') <li><a href="{{ route('degustacioni-pakets.index') }}">Paketi</a></li> @endcan
        </ul>
      </div>

      <div class="col-6 col-md-4">
        <h6 class="mb-2" style="font-weight:800">Kontakt</h6>
        <ul class="list-unstyled">
          <li><i class="icon ion-md-mail"></i> <a href="mailto:info@espivara.rs">info@espivara.rs</a></li>
          <li><i class="icon ion-md-call"></i> <a href="tel:+381601234000">+381 60 123 4000</a></li>
          <li><i class="icon ion-md-pin"></i> Cara Dušana 7, Beograd</li>
        </ul>
      </div>
    </div>

    <div class="d-flex justify-content-between py-3" style="border-top:1px solid rgba(224,161,43,.18); font-size:.95rem">
      <span>© {{ date('Y') }} ES Pivara — Sva prava zadržana</span>
      <span><a href="#">Uslovi korišćenja</a> · <a href="#">Privatnost</a></span>
    </div>
  </div>
</footer>
