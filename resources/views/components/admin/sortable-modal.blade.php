@props([
    'id' => 'sortableModal',        // ID modal & tbody
    'title' => 'Sort Items',        // Judul modal
    'items' => [],                  // Collection / array data
    'columns' => [                  // Array of columns: ['title' => 'Title', 'key' => 'field', 'type' => 'text|image']
        ['title' => 'Image', 'key' => 'image', 'type' => 'image'],
        ['title' => 'Title', 'key' => 'title', 'type' => 'text']
    ],
    'updateRoute' => ''             // Route untuk AJAX update
])


<button type="button" class="btn btn-secondary text-xs btn-sm px-8 py-8 radius-6 d-flex align-items-center gap-1"
    data-bs-toggle="modal" data-bs-target="#sortModal">
    <iconify-icon icon="mdi:sort" class="icon-sm line-height-1"></iconify-icon>
    <span>Sort</span>
</button>
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="mdi:information" class="text-xl"></iconify-icon>
                        <span>Drag and drop untuk mengubah urutan tampilan</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                @foreach ($columns as $col)
                                    <th>{{ $col['title'] }}</th>
                                @endforeach
                                <th></th> {{-- Drag handle --}}
                            </tr>
                        </thead>
                        <tbody id="{{ $id }}-tbody">
                            @foreach($items->sortBy('order') as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    @foreach ($columns as $col)
                                        @php
                                            $value = $item[$col['key']] ?? null;
                                        @endphp
                                        @if($value)
                                            <td>
                                                @if($col['type'] === 'image')
                                                    <img src="{{ asset('storage/' . $value) }}" alt=""
                                                        style="width:50px;height:50px;object-fit:cover;">
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                    <td class="text-center">
                                        <iconify-icon icon="mdi:drag" class="text-xl text-neutral-500 cursor-move"></iconify-icon>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@once
<link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3.0.0/dist/iconify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
@endonce

<script>
$(document).ready(function() {
    let tbodyId = "#{{ $id }}-tbody";

    $(tbodyId).sortable({
        update: function(event, ui) {
            let order = [];
            $(tbodyId + " tr").each(function(index) {
                order.push({
                    id: $(this).data("id"),
                    order: index + 1
                });
            });

            $.ajax({
                url: "{{ $updateRoute }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order: order
                },
                success: function(response) {
                    if(response.success) {
                        toastr.success("Urutan berhasil diperbarui");
                        $(tbodyId + " tr").each(function(index) {
                            $(this).find("td:first").text(index + 1);
                        });
                    } else {
                        toastr.error("Gagal memperbarui urutan");
                    }
                },
                error: function() {
                    toastr.error("Terjadi kesalahan saat memperbarui urutan");
                }
            });
        }
    });
});
</script>
