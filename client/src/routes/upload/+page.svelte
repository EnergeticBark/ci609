<script>
</script>

<main>
    <h2>Add new sightings</h2>
    <form action="#">
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
    </form>
</main>

<style>
    #status {
        padding: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid var(--surface-1);
        background-color:  var(--base);
        color: var(--text);
    }

    /* Grid layout */
    label { grid-area: label; }
    #status { grid-area: query; }
    button { grid-area: button; }

    form {
        display: grid;
        grid-gap: 0.5rem;
        grid-auto-columns: 1fr auto;
        grid-template-areas:
            "label ."
            "query button";
    }
</style>