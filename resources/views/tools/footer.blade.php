 <footer class="footer pt-3">
     <div class="container-fluid">
         <div class="row justify-content-center">
             <div class="col-lg-8">
                 <div class="copyright text-center text-sm text-white">
                     {{ __('Copyright For') }}
                     {{ config('app.name' ?? 'Mindly') }} ©
                     <script>
                         document.write(new Date().getFullYear())

                     </script>,
                     {{ __('Developed by') }}
                     <a href="https://www.facebook.com/mtg.tech.egy" class="font-weight-bold text-danger" target="_blank">MahgoubTech</a>
                 </div>
             </div>
         </div>
     </div>
 </footer>
