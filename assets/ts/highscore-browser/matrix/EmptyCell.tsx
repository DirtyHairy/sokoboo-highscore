import styled from '@emotion/styled';

import { IS_FIREFOX } from '../../util/sniffing';
import { BORDER, WIDE } from './definitions';

const EmptyCell = styled.td({
    height: WIDE,
    width: WIDE,
    border: BORDER,
    backgroundClip: IS_FIREFOX ? 'padding-box' : 'border-box',
    backgroundColor: '#333'
});

export default EmptyCell;
