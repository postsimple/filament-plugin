<script>
    if (!window.__postsimpleOpenUrlListenerInitialized) {
        window.__postsimpleOpenUrlListenerInitialized = true;
        window.addEventListener('open-url-in-new-tab', function (event) {
            var url = event && event.detail ? event.detail.url : null;
            if (!url) {
                return;
            }
            window.open(url, '_blank', 'noopener');
        });
    }
</script>
