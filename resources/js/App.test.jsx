import { it } from 'vitest';
import React from 'react';
import { render } from '@testing-library/react';
import Dashboard from './Dashboard';

it('renders the admin page', () => {
    render(<Dashboard />);
});
