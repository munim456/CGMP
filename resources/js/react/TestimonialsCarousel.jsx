import { useCallback, useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion, useReducedMotion } from 'framer-motion';

const AUTOPLAY_MS = 6500;

function ChevronIcon({ direction, className }) {
    const d = direction === 'left' ? 'm15 18-6-6 6-6' : 'm9 18 6-6-6-6';
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor"
            strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            <path d={d} />
        </svg>
    );
}

const variants = {
    enter: (dir) => ({ opacity: 0, x: dir > 0 ? 24 : -24 }),
    center: { opacity: 1, x: 0 },
    exit: (dir) => ({ opacity: 0, x: dir > 0 ? -24 : 24 }),
};

export default function TestimonialsCarousel({ testimonials }) {
    const [[index, direction], setIndex] = useState([0, 0]);
    const prefersReducedMotion = useReducedMotion();
    const timerRef = useRef(null);
    const count = testimonials.length;

    const goTo = useCallback((next, dir) => {
        setIndex(([current]) => {
            const wrapped = (next + count) % count;
            return [wrapped, dir ?? (wrapped > current ? 1 : -1)];
        });
    }, [count]);

    const stop = useCallback(() => {
        if (timerRef.current) clearInterval(timerRef.current);
    }, []);

    const play = useCallback(() => {
        stop();
        if (prefersReducedMotion || count < 2) return;
        timerRef.current = setInterval(() => {
            setIndex(([current]) => [(current + 1) % count, 1]);
        }, AUTOPLAY_MS);
    }, [count, prefersReducedMotion, stop]);

    useEffect(() => {
        play();
        return stop;
    }, [play, stop]);

    if (count === 0) return null;

    const current = testimonials[index];
    const touchStartX = useRef(null);

    return (
        <div
            className="testimonial-slider"
            onMouseEnter={stop}
            onMouseLeave={play}
            onFocus={stop}
            onBlur={(e) => {
                if (!e.currentTarget.contains(e.relatedTarget)) play();
            }}
            onTouchStart={(e) => { touchStartX.current = e.touches[0].clientX; }}
            onTouchEnd={(e) => {
                if (touchStartX.current === null) return;
                const delta = e.changedTouches[0].clientX - touchStartX.current;
                if (Math.abs(delta) > 45) goTo(index + (delta < 0 ? 1 : -1));
                touchStartX.current = null;
                play();
            }}
        >
            <button type="button" className="slider-btn slider-btn--prev" aria-label="Previous testimonial"
                onClick={() => { goTo(index - 1, -1); play(); }}>
                <ChevronIcon direction="left" className="icon w-6 h-6" />
            </button>

            <div className="testimonial-track" style={{ position: 'relative', overflow: 'hidden' }}>
                <AnimatePresence mode="wait" custom={direction} initial={false}>
                    <motion.figure
                        key={index}
                        className="testimonial-slide is-current"
                        custom={direction}
                        variants={variants}
                        initial="enter"
                        animate="center"
                        exit="exit"
                        transition={{ duration: prefersReducedMotion ? 0 : 0.4, ease: 'easeOut' }}
                    >
                        <blockquote>{current.content}</blockquote>
                        <figcaption>
                            <strong>{current.name}</strong>
                            {current.context ? <span>{current.context}</span> : null}
                        </figcaption>
                    </motion.figure>
                </AnimatePresence>
            </div>

            <button type="button" className="slider-btn slider-btn--next" aria-label="Next testimonial"
                onClick={() => { goTo(index + 1, 1); play(); }}>
                <ChevronIcon direction="right" className="icon w-6 h-6" />
            </button>

            <div className="slider-dots" role="tablist" aria-label="Choose testimonial">
                {testimonials.map((t, i) => (
                    <button
                        key={i}
                        type="button"
                        role="tab"
                        aria-selected={i === index}
                        aria-label={`Testimonial ${i + 1}`}
                        onClick={() => { goTo(i); play(); }}
                    />
                ))}
            </div>
        </div>
    );
}
