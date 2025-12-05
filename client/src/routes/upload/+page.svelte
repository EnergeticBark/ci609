<script>
</script>

<main>
    <h2>Add new sightings</h2>
    <form action="#">
        <label for="image">Image:</label>
        <!-- On all major mobile browsers capture="environment" will prompt the user to take a photo. -->
        <!-- See: https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Attributes/capture -->
        <input type="file" id="image" accept="image/png, image/jpeg" capture="environment" />
        <label for="status">Pangolin status:</label>
        <select id="status">
            <optgroup label="Alive">
                <option value="">Alive</option>
            </optgroup>
            <optgroup label="Dead">
                <option value="fence">Caught on fence</option>
                <option value="fenceElectrocuted">Electrocuted on fence</option>
                <option value="road">Killed on road</option>
            </optgroup>
        </select>
        <label for="notes">Additional details, such as the type of fence or road. (optional):</label>
        <textarea id="notes"></textarea>
    </form>
</main>

<style>
    select, textarea {
        border-radius: 0.5rem;
        border: 1px solid var(--surface-1);
        background-color:  var(--base);
        color: var(--text);
    }

    #notes {
        min-height: 9rem;
        resize: vertical;
        padding: 0.5rem;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
</style>