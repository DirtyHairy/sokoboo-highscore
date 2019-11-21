import styled from '@emotion/styled';

import { BORDER, WIDE } from './definitions';

const EmptyCell = styled.td({
    height: WIDE,
    width: WIDE,
    border: BORDER,
    backgroundColor: '#333'
});

export default EmptyCell;
