import '../../scss/register-highscore.scss';

type MessageMap = {
    [key in keyof ValidityState]?: string;
};

const NICK_INPUT_ID = 'input-nick';
const CODE_INPUT_ID = 'input-code';

function handleValidation(target: HTMLInputElement, messages: MessageMap): void {
    const handler = () => {
        for (const validation of Object.keys(messages) as Array<keyof ValidityState>) {
            if (!messages.hasOwnProperty(validation)) {
                continue;
            }

            if (target.validity[validation]) {
                target.setCustomValidity(messages[validation]!);
                return;
            }
        }

        target.setCustomValidity('');
    };

    if (!target.validity || !target.setCustomValidity) {
        return;
    }

    target.addEventListener('input', handler);

    handler();
}

window.addEventListener('load', () => {
    handleValidation(document.getElementById(NICK_INPUT_ID) as HTMLInputElement, {
        tooLong: 'The name must not be longer than 20 characters.',
        valueMissing: 'A name is required.',
        patternMismatch: 'The name may only contain letters, numbers, spaces and dashes.'
    });

    var CODE_MESSAGE = 'The code must be numeric and must have exactly 12 digits.';

    handleValidation(document.getElementById(CODE_INPUT_ID) as HTMLInputElement, {
        tooLong: CODE_MESSAGE,
        tooShort: CODE_MESSAGE,
        valueMissing: CODE_MESSAGE,
        patternMismatch: CODE_MESSAGE
    });
});
