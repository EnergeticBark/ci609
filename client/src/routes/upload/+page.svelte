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
        border-radius: 0.5rem;
        border: 1px solid var(--surface-1);
        background-color:  var(--base);
        color: var(--text);
    }

    /* Grid layout */
    label[for="status"] { grid-area: status_label; }
    #status { grid-area: status; }
    label[for="notes"] { grid-area: notes_label; }
    #notes { grid-area: notes; }
    button { grid-area: button; }

    form {
        display: grid;
        grid-gap: 0.5rem;
        grid-auto-columns: 1fr auto;
        grid-template-areas:
            "status_label ."
            "status ."
            "notes_label ."
            "notes .";
    }
</style>