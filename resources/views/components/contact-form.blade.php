@props(['services'])
<div class="relative min-w-0 overflow-hidden rounded-2xl border border-line bg-white p-6 shadow-lift sm:p-9 lg:p-11" data-reveal>
    <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-coral via-coral/70 to-coral/20" aria-hidden="true"></span>
    <div class="mb-8 flex items-start gap-4 border-b border-line pb-7">
        <span class="icon-badge"><x-line-icon name="message" class="size-5" /></span>
        <div><h2 class="font-heading text-xl font-bold text-charcoal sm:text-2xl">Envoyez-nous votre demande</h2><p class="mt-1 text-sm text-slate-500">Les champs marqués d’un astérisque (*) sont obligatoires.</p></div>
    </div>
    @if(session('success'))<div class="mb-7 rounded-md border-l-4 border-emerald-500 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-7 rounded-md border-l-4 border-coral bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="mb-7 rounded-md border-l-4 border-coral bg-red-50 p-4 text-sm text-red-800">Merci de corriger les champs signalés.</div>@endif
    <form action="{{ route('contact.store') }}" method="POST" class="grid gap-5 sm:grid-cols-2" data-contact-form>@csrf
        <div class="sm:col-span-2"><label class="form-label" for="full_name">Nom complet *</label><input class="form-input" id="full_name" name="full_name" value="{{ old('full_name') }}" autocomplete="name" required>@error('full_name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="email">E-mail *</label><input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required>@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="phone">Téléphone</label><input class="form-input" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">@error('phone')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="company">Société</label><input class="form-input" id="company" name="company" value="{{ old('company') }}" autocomplete="organization">@error('company')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label" for="service">Service souhaité</label><select class="form-input" id="service" name="service"><option value="">À préciser</option>@foreach($services as $item)<option @selected(old('service', request('service')) === $item->title)>{{ $item->title }}</option>@endforeach<option @selected(old('service') === 'Autre')>Autre</option></select>@error('service')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div class="sm:col-span-2"><label class="form-label" for="message">Message *</label><textarea class="form-input min-h-36" id="message" name="message" required>{{ old('message') }}</textarea>@error('message')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div class="sm:col-span-2"><x-button-primary type="submit" class="w-full sm:w-auto" data-contact-submit><span data-contact-submit-label>Envoyer la demande</span></x-button-primary><p class="mt-4 text-xs leading-5 text-slate-400">En envoyant ce formulaire, vous acceptez notre <a class="underline hover:text-coral" href="{{ route('confidentialite') }}">politique de confidentialité</a>.</p></div>
    </form>
</div>
