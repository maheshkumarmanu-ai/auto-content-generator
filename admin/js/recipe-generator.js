
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.auto-blog-form');
            const loader = document.getElementById('loader');

            form.addEventListener('submit', function () {
                loader.style.display = 'flex'; // Show loader
            });
        });
 