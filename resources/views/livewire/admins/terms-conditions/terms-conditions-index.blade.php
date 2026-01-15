<div>
    <div class="row mb-4">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa-solid fa-scale-balanced text-dark text-sm opacity-10"></i>
                        {{ __('Terms And Condetions') }}
                        <div class="float-end">
                            @include('tools.spinner')
                        </div>
                    </h5>
                </div>
                <div class="card-body">
                    @if ($action == 'create')
                    @include('livewire.admins.terms-conditions.terms-conditions-create')
                    @else
                    @include('livewire.admins.terms-conditions.terms-conditions-show')
                    @endif
                </div>
                <div class="card-footer text-muted">
                    آخر تحديث: {{ now()->format('Y-m-d') }}
                </div>
            </div>
        </div>
    </div>
</div>
@script
@include('tools.message')
@endscript

@push('js')
<script>
    // دالة لبدء المحرر
    function initEditor() {
        const editorElement = document.querySelector('#editor');
        if (!editorElement) return;

        ClassicEditor
            .create(editorElement, {
                // يمكنك إضافة إعدادات هنا مثل الـ Toolbar
                language: 'ar'
            })
            .then(editor => {
                let debounceTimer;
                editor.model.document.on('change:data', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        let data = editor.getData();
                        @this.set('content', data);
                    }, 500); // إرسال البيانات بعد توقف الكتابة بـ 500 ملي ثانية
                });
            })
            .catch(error => {
                console.error(error);
            });
    }

    // تشغيل الدالة عند تحميل الصفحة أول مرة
    document.addEventListener('DOMContentLoaded', initEditor);

    // // تشغيل الدالة عند التنقل عبر Livewire (لو بتستخدم wire:navigate)
    // document.addEventListener('livewire:navigated', initEditor);
    window.addEventListener('clearEditor', event => {
        if (editorInstance) {
            editorInstance.setData(''); // تفريغ محتوى المحرر تماماً
        }
    });


    window.addEventListener('contentChanged', event => {
        if (editorInstance) {
            editorInstance.setData(event.detail.content);
        }
    });

</script>
@endpush
