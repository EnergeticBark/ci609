<script>
    const { maxsize, ...props } = $props();

    const checkFileSize = (event) => {
        const inputNode = event.currentTarget;
        const files = inputNode.files;
        if (files.length > 0) {
            if (files[0].size > maxsize * 1e6) {
                inputNode.setCustomValidity(
                    `The image must not be larger than ${maxsize} MB`,
                );
                inputNode.reportValidity();
                return;
            }
        }

        // Empty string means the input is valid.
        inputNode.setCustomValidity("");
    };
</script>

<input type="file" {...props} onchange={checkFileSize} />

<style>
    input {
        width: 100%;
    }
</style>
