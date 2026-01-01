
<script>
    function confirmDelete(id, method = 'delete') {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لا يمكنك التراجع بعد الحذف!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch(method, { id: id });
            }
        })
    }
</script>
