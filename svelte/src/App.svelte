<script lang="ts">
    import TextInput from "./lib/components/TextInput.svelte";
    import SelectInput from "./lib/components/SelectInput.svelte";
    import { accountData } from "./lib/stores/accountData";

    const choices: Array<string> = ["Compiègne", "Paris", "Amiens"];

    $: isError = Object.values($accountData).some((d) => "" !== d.error);
    $: $accountData, accountData.handleErrors();

    function onSubmit(): void {
        if (!isError) {
            alert(JSON.stringify(Object.values($accountData).map((d) => d.value)));
        }
    }
</script>

<main class="app">
    <h1>Svelte</h1>
    <form id="form" on:submit|preventDefault={onSubmit}>
        <TextInput
            name="username"
            type="text"
            label="Nom d'utilisateur"
            placeholder="Jean"
            bind:value={$accountData.username.value}
            error={$accountData.username.error}
            touched={$accountData.username.touched}
            on:change|once={() => accountData.setTouched("username")}
        />
        <TextInput
            name="email"
            type="email"
            label="Adresse email"
            placeholder="jean@gmail.com"
            bind:value={$accountData.email.value}
            error={$accountData.email.error}
            touched={$accountData.email.touched}
            on:change|once={() => accountData.setTouched("email")}
        />
        <TextInput
            name="phoneNumber"
            type="tel"
            label="Numéro de téléphone"
            placeholder="0690805542"
            bind:value={$accountData.phoneNumber.value}
            error={$accountData.phoneNumber.error}
            touched={$accountData.phoneNumber.touched}
            on:change|once={() => accountData.setTouched("phoneNumber")}
        />
        <TextInput
            name="password"
            type="password"
            label="Mot de passe"
            bind:value={$accountData.password.value}
            error={$accountData.password.error}
            touched={$accountData.password.touched}
            on:change|once={() => accountData.setTouched("password")}
        />
        <SelectInput
            name="city"
            label="Ville"
            bind:value={$accountData.city.value}
            error={$accountData.city.error}
            touched={$accountData.city.touched}
            {choices}
            on:change|once={() => accountData.setTouched("city")}
        />
    </form>
    <button type="submit" form="form" class:disabled={isError} disabled={isError}>Envoyer</button>
</main>

<style>
    h1 {
        font-size: 3.2em;
        line-height: 1.1;
    }

    .app {
        background-color: #ffffff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: column;
        padding: 25px;
        box-shadow: 12px 12px 2px 1px rgba(0, 0, 255, 0.2);
        min-width: 320px;
    }

    form {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin: 20px 0;
    }

    button {
        border-radius: 8px;
        border: 1px solid transparent;
        padding: 0.6em 1.2em;
        font-size: 1em;
        font-weight: 500;
        font-family: inherit;
        background-color: #2d8565;
        cursor: pointer;
        transition: border-color 0.25s;
        color: #ffffff;
    }
    button:hover:not(.disabled) {
        border-color: #14440b;
    }
    button:focus:not(.disabled),
    button:focus-visible:not(.disabled) {
        outline: 4px auto -webkit-focus-ring-color;
    }
    button.disabled {
        opacity: 0.5;
        background-color: #42b88c;
        cursor: not-allowed;
    }
</style>
