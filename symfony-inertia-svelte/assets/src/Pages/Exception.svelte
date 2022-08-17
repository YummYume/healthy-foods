<script>
    import { title, description } from "@stores/seo";

    export let status;

    $: exceptionTitle = {
        503: "503: Service Unavailable",
        500: "500: Server Error",
        404: "404: Page Not Found",
        403: "403: Forbidden"
    }[status];

    $: exceptionDescription = {
        503: "Sorry, we are doing some maintenance. Please check back soon.",
        500: "Whoops, something went wrong on our servers.",
        404: "Sorry, the page you are looking for could not be found.",
        403: "Sorry, you are forbidden from accessing this page."
    }[status];

    $: title.set(exceptionTitle);
    $: description.set(exceptionDescription);
</script>

<div>
    <h2 class="w-full text-center mb-10 text-4xl">{exceptionTitle}</h2>
    <p class="text-lg tracking-wide leading-7 my-3">{exceptionDescription}</p>
</div>
