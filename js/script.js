function toUpperCase(element) {
            const cursorPosition = element.selectionStart;
            element.value = element.value.toUpperCase();
            element.setSelectionRange(cursorPosition, cursorPosition);
        }