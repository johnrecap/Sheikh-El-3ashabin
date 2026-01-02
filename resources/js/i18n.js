import { createI18n } from "vue-i18n";

function loadMessages() {
    const modules = import.meta.glob("./languages/*.json", { eager: true });
    const messages = Object.keys(modules).reduce((messages, path) => {
        const locale = path.match(/\/([a-z0-9-_]+)\.json$/i)[1];
        messages[locale] = modules[path];
        return messages;
    }, {});

    return messages;
}

const messages = loadMessages();

const i18n = createI18n({
    legacy: false,
    locale: "en",
    fallbackLocale: "en",
    messages,
});

export default i18n;
