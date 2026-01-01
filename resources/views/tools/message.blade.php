<script>
    $wire.on('message' , (event)=>{
        Swal.fire({
            icon: 'success',
            position: "center",
            title: event.message,
            showConfirmButton: false,
            timer: 1500
            });
    });
    $wire.on('error' , (event)=>{
        Swal.fire({
            icon: 'error',
            position: "center",
            title: event.message,
            showConfirmButton: false,
            timer: 1500
            });
    });
</script>
