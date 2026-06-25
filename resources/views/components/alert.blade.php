@if (session()->has('success'))
    <script>
        showNotification("{{ session()->get('success') }}", 'success');
    </script>
@endif
@if (session()->has('warning'))
    <script>
        showNotification("{{ session()->get('warning') }}", 'warning');
    </script>
@endif
@if (session()->has('info'))
    <script>
        showNotification("{{ session()->get('info') }}", 'info');
    </script>
@endif
@if (session()->has('error'))
    <script>
        showNotification("{{ session()->get('error') }}", 'error');
    </script>
@endif