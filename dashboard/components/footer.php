</div>
<!-- Footer -->
<footer class="mt-auto">
    <div class="border-t border-slate-700/50 bg-slate-800/50 backdrop-blur-sm">
        <div class="px-6 py-4">
            <div class="flex flex-col items-center justify-center gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <p class="text-sm font-medium text-white">ZCarWash</p>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-blue-500/10 text-blue-500 font-medium">
                        V1.0.0
                    </span>
                </div>
                <p class="text-sm text-slate-400">
                    Developed By Muhammad Faza Husnan
                </p>
                <p class="text-xs text-slate-500">
                    &copy; <?= date('Y') ?> All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
</main>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainContent = document.querySelector('main');

        function setMainHeight() {
            mainContent.style.minHeight = '100vh';
            mainContent.style.display = 'flex';
            mainContent.style.flexDirection = 'column';
        }

        setMainHeight();
        window.addEventListener('resize', setMainHeight);
    });
</script>

</body>

</html>