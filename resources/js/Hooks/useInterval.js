import { useRef, useEffect } from 'react';

const useInterval = (callback, delay) => {
    // from https://stackoverflow.com/a/59274004
    const intervalRef = useRef(null);
    const callbackRef = useRef(callback);

    // Remember the latest callback:
    //
    // Without this, if you change the callback, when setInterval ticks again, it
    // will still call your old callback.
    //
    // If you add `callback` to useEffect's deps, it will work fine but the
    // interval will be reset.

    useEffect(() => {
        callbackRef.current = callback;
    }, [callback]);

    // Set up the interval:

    useEffect(() => {
        if (typeof delay === 'number') {
            intervalRef.current = window.setInterval(
                () => callbackRef.current(),
                delay,
            );

            // Clear interval if the components is unmounted or the delay changes:
            return () => window.clearInterval(intervalRef.current);
        }
        return intervalRef;
    }, [delay]);

    // Returns a ref to the interval ID in case you want to clear it manually:
    return intervalRef;
};

export default useInterval;
