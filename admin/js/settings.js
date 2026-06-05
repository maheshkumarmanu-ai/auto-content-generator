
    document.addEventListener("DOMContentLoaded", function () {

        const containers = document.querySelectorAll(".api-provider");



        containers.forEach(container => {

            const checkbox = container.querySelector('input[type="checkbox"]');

            const inputs = container.querySelectorAll('input[type="text"], input[type="password"], select');



            function toggleInputs() {

                if (!checkbox.checked) {

                    inputs.forEach(input => {

                        if (input !== checkbox) input.disabled = true;

                    });

                    container.classList.add('disabled');

                } else {

                    inputs.forEach(input => input.disabled = false);

                    container.classList.remove('disabled');

                }

            }



            checkbox.addEventListener('change', toggleInputs);

            toggleInputs();

        });

    });
