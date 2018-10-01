bs.util.registerNamespace( 'bs.tgcld.util.tag' );
bs.tgcld.util.tag.TagCloudDefinition = function BsVecUtilTagCloudDefinition() {
	bs.tgcld.util.tag.TagCloudDefinition.super.call( this );
};

OO.inheritClass( bs.tgcld.util.tag.TagCloudDefinition, bs.vec.util.tag.Definition );

bs.tgcld.util.tag.TagCloudDefinition.prototype.getCfg = function() {
	var cfg = bs.tgcld.util.tag.TagCloudDefinition.super.prototype.getCfg.call( this );
	return $.extend( cfg, {
		classname : 'TagCloud',
		name: 'tagCloud',
		tagname: 'bs:tagcloud',
		descriptionMsg: 'bs-tagcloud-tag-tagcloud-desc',
		menuItemMsg: 'bs-tagcloud-ve-tagcloud-title',
		tabbed: true,
		attributes: [{
			name: 'renderer',
			labelMsg: '##renderer',
			helpMsg: '##translate',
			type: 'dropdown',
			default: 'list',
			tab: 'general',
			options: [
				{ data: 'text', label: mw.message( '##text' ).plain() },
				{ data: 'list', label: mw.message( '##list' ).plain() },
				{ data: 'canvas3d', label: mw.message( '##canvas3d' ).plain() }
			]
		},{
			name: 'store',
			labelMsg: '##store',
			helpMsg: '##translate',
			type: 'dropdown',
			default: 'category',
			tab: 'general',
			options: [
				{ data: 'category', label: mw.message( '##category' ).plain() },
				{ data: 'searchstats', label: mw.message( '##searchstats' ).plain() }
			]
		},{
			name: 'width',
			labelMsg: '##width',
			helpMsg: '##translate',
			type: 'percent',
			default: '100',
			tab: 'advanced'
		},{
			name: 'showcount',
			labelMsg: '##showcount',
			helpMsg: '##translate',
			type: 'toggle',
			default: true,
			tab: 'advanced'
		},{
			name: 'maxsize',
			labelMsg: '##maxsize',
			helpMsg: '##translate',
			type: 'number',
			default: 30,
			tab: 'advanced'
		},{
			name: 'minsize',
			labelMsg: '##minsize',
			helpMsg: '##translate',
			type: 'number',
			default: 5,
			tab: 'advanced'
		},{
			name: 'exclude',
			labelMsg: '##exclude',
			helpMsg: '##translate',
			type: 'text',
			default: '',
			tab: 'advanced'
		}]
	});
};

bs.vec.registerTagDefinition(
	 new bs.tgcld.util.tag.TagCloudDefinition()
);
