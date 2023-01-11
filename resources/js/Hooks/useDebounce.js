import { useRef, useEffect, useCallback } from 'react';
import { debounce } from 'lodash';

function useDebounce(delay, callback) {
    const inputsRef = useRef(callback);

    useEffect(() => {
        inputsRef.current = { callback, delay };
    });

    return useCallback(
        debounce((...args) => {
            if (inputsRef.current.delay === delay) {
                inputsRef.current.callback(...args);
            }
        }, delay),
        [delay, debounce],
    );
}

export default useDebounce;
