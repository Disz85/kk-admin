import { it } from 'vitest';
import React from 'react';
import { render } from '@testing-library/react';
import Cms from './Cms';

it('renders the admin page', () => {
    render(<Cms />);
});
