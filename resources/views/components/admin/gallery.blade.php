@if (!empty($model->images) && $model->images->count() > 0)
    <div class="mb-4">
        <form action="{{ route($routeBase.'.destroy-image', $model->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="form-label fw-bold fs-5 mb-0">Property Gallery</label>
                {{-- Tombol Delete --}}
                <button type="submit" class="btn btn-danger" onclick="deleteData(event, this)">
                    Delete Selected Image
                </button>
            </div>

            <div class="row g-3">
                @foreach ($model->images as $image)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 position-relative gallery-item">
                            <img src="{{ asset('storage/' . $image->image) }}" class="card-img-top" alt="Property image"
                                style="height: 200px; object-fit: cover;">

                            {{-- Checkbox untuk multi delete --}}
                            <div class="form-check position-absolute top-0 m-1">
                                <input type="checkbox" class="form-check-input" name="files[]" value="{{ $image->id }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </form>
    </div>
@endif
