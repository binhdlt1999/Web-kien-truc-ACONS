@props(['services'])

<form action="{{ route('contacts.store') }}" method="POST" class="contact-inquiry-form" novalidate>
    @csrf
    <div class="honeypot" aria-hidden="true">
        <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
    </div>

    @if($errors->any())
        <div class="contact-form-alert" role="alert">Vui lòng kiểm tra lại các thông tin được đánh dấu bên dưới.</div>
    @endif

    <div class="contact-form-grid">
        <div class="contact-field">
            <label for="contact-name">Họ và tên <span>*</span></label>
            <input id="contact-name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nguyễn Văn An" required maxlength="100" autocomplete="name">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="contact-field">
            <label for="contact-email">Địa chỉ email</label>
            <input id="contact-email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="email@congty.vn" maxlength="255" autocomplete="email">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="contact-field">
            <label for="contact-phone">Số điện thoại <span>*</span></label>
            <input id="contact-phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="0900 000 000" required maxlength="30" autocomplete="tel">
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="contact-field">
            <label for="contact-service">Dịch vụ quan tâm</label>
            <select id="contact-service" name="service_id" class="form-select @error('service_id') is-invalid @enderror">
                <option value="">Chọn một dịch vụ</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" @selected(old('service_id', request('service')) == $service->id)>{{ $service->name }}</option>
                @endforeach
            </select>
            @error('service_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="contact-field contact-field-full">
            <label for="contact-message">Thông tin dự án</label>
            <textarea id="contact-message" name="message" rows="6" class="form-control @error('message') is-invalid @enderror" placeholder="Hãy mô tả loại công trình, địa điểm, diện tích, nhu cầu và thời gian dự kiến..." maxlength="3000">{{ old('message') }}</textarea>
            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <button class="btn btn-acons contact-submit" type="submit">Gửi yêu cầu tư vấn <i class="bi bi-arrow-right"></i></button>
    <p class="contact-form-privacy"><i class="bi bi-shield-check"></i> Thông tin của bạn chỉ được sử dụng để ACONS tư vấn và phản hồi yêu cầu này.</p>
</form>
