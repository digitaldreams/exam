<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
@yield('scripts')
<script type="text/javascript">
    // Hide submenus
    $('#body-row .collapse').collapse('hide');

    // Collapse/Expand icon
    $('#collapse-icon').addClass('fa-angle-double-left');

    // Collapse click
    $('[data-toggle=sidebar-colapse]').click(function () {
        SidebarCollapse();
    });

    function SidebarCollapse() {
        $('.menu-collapsed').toggleClass('d-none');
        $('.sidebar-submenu').toggleClass('d-none');
        $('.submenu-icon').toggleClass('d-none');
        $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');
        $('#sidebar-container').toggleClass('d-none');
        // Treating d-flex/d-none on separators with title
        var SeparatorTitle = $('.sidebar-separator-title');
        if (SeparatorTitle.hasClass('d-flex')) {
            SeparatorTitle.removeClass('d-flex');
        } else {
            SeparatorTitle.addClass('d-flex');
        }

        // Collapse/Expand icon
        $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
    }

    function checkSize(max_img_size, id) {
        var input = document.getElementById(id);
        var allowedImageMimeType = [
            'image/svg+xml',
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/webp'
        ];

        if (input.files && input.files.length == 1) {
            if (allowedImageMimeType.indexOf(input.files[0].type) == -1) {
                alert('File Type Not allowed. Only jpg,jpeg,png,webp,svg allowed');
                input.value = '';
                return false;
            }
            if (input.files[0].size > max_img_size) {
                var yourFileSize = (input.files[0].size / 1024 / 1024);
                input.value = '';
                alert("The file must be less than " + (max_img_size / 1024 / 1024) + "MB", "Your file size is " + yourFileSize.toFixed(2) + 'MB', "warning")
                return false;
            } else {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + id + '_preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        return true;
    }
</script>
@yield('script')
@yield('scripts')
</body>

</html>
