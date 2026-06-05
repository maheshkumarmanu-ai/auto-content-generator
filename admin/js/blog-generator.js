

        document.addEventListener('DOMContentLoaded', function () {

            const tabs = document.querySelectorAll('#tone-tabs .tone-tab');

            const toneInput = document.getElementById('blog_tone');



            tabs.forEach(tab => {

                tab.addEventListener('click', function () {

                    tabs.forEach(t => t.classList.remove('active'));

                    this.classList.add('active');

                    toneInput.value = this.getAttribute('data-tone');

                });

            });

        });



        document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('#tone-tabs .tone-tab');
    const toneInput = document.getElementById('blog_tone');
    const loader = document.getElementById('loader');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            toneInput.value = this.getAttribute('data-tone');
        });
    });

    // Show loader on form submit
    const form = document.querySelector('.auto-blog-form');
    form.addEventListener('submit', function () {
        loader.style.display = 'flex'; // Show loader
    });
});



