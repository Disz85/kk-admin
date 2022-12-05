import { it } from 'vitest';
import React from 'react';
import { render } from '@testing-library/react';
import Dashboard from './Cms';

it('renders the admin page', () => {
    render(<Dashboard />);
});
