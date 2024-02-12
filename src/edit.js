/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl, SelectControl, RangeControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';


export default function Edit({ attributes, setAttributes }) {
	const { category, tag, id, hstart, order, sort, lang, additional_class, color, load_open, expand_all_link, hide_title, hide_accordion, glossarystyle, glossary } = attributes;
	const blockProps = useBlockProps();
	const [categorystate, setSelectedCategories] = useState(['']);
	const [tagstate, setSelectedTags] = useState(['']);
	const [idstate, setSelectedIDs] = useState(['']);

	useEffect(() => {
		setAttributes({ category: category });
		setAttributes({ tag: tag });
		setAttributes({ id: id });
		setAttributes({ hstart: hstart });
		setAttributes({ order: order });
		setAttributes({ sort: sort });
		setAttributes({ lang: lang });
		setAttributes({ additional_class: additional_class });
		setAttributes({ color: color });
		setAttributes({ load_open: load_open });
		setAttributes({ expand_all_link: expand_all_link });
		setAttributes({ hide_title: hide_title });
		setAttributes({ hide_accordion: hide_accordion });
		setAttributes({ glossarystyle: glossarystyle });
		setAttributes({ glossary: glossary });
	}, [category, tag, id, hstart, order, sort, lang, additional_class, color, load_open, expand_all_link, hide_title, hide_accordion, glossarystyle, glossary, setAttributes]);



	const categories = useSelect((select) => {
		return select('core').getEntityRecords('taxonomy', 'faq_category');
	}, []);

	const categoryoptions = [
		{
			label: __('all', 'rrze-lexikon'),
			value: ''
		}
	];

	if (!!categories) {
		Object.values(categories).forEach(category => {
			categoryoptions.push({
				label: category.name,
				value: category.slug,
			});
		});
	}

	const tags = useSelect((select) => {
		return select('core').getEntityRecords('taxonomy', 'faq_tag');
	}, []);

	const tagoptions = [
		{
			label: __('all', 'rrze-lexikon'),
			value: ''
		}
	];

	if (!!tags) {
		Object.values(tags).forEach(tag => {
			tagoptions.push({
				label: tag.name,
				value: tag.slug,
			});
		});
	}

	const faqs = useSelect((select) => {
		return select('core').getEntityRecords('postType', 'faq', { per_page: -1, orderby: 'title', order: "asc" });
	}, []);

	const faqoptions = [
		{
			label: __('all', 'rrze-lexikon'),
			value: 0
		}
	];

	if (!!faqs) {
		Object.values(faqs).forEach(faq => {
			faqoptions.push({
				label: faq.title.rendered ? faq.title.rendered : __('No title', 'rrze-lexikon'),
				value: faq.id,
			});
		});
	}

	// const languages = useSelect((select) => {
	// 	return select('core').getEntityRecords('term', 'lang', { per_page: -1 });
	// }, []);

	// console.log('edit.js languages: ' + JSON.stringify(languages));

	const langoptions = [
		{
			label: __('all', 'rrze-lexikon'),
			value: ''
		}
	];

	// if (!!languages) {
	// 	Object.values(languages).forEach(language => {
	// 		langoptions.push({
	// 			label: language.name,
	// 			value: language.id,
	// 		});
	// 	});
	// }

	const glossaryoptions = [
		{
			label: __('none', 'rrze-lexikon'),
			value: ''
		},
		{
			label: __('Categories', 'rrze-lexikon'),
			value: 'category'
		},
		{
			label: __('Tags', 'rrze-lexikon'),
			value: 'tag'
		}
	];

	const glossarystyleoptions = [
		{
			label: __('-- hidden --', 'rrze-lexikon'),
			value: ''
		},
		{
			label: __('A - Z', 'rrze-lexikon'),
			value: 'a-z'
		},
		{
			label: __('Tagcloud', 'rrze-lexikon'),
			value: 'tagcloud'
		},
		{
			label: __('Tabs', 'rrze-lexikon'),
			value: 'tabs'
		}
	];

	const coloroptions = [
		{
			label: 'fau',
			value: 'fau'
		},
		{
			label: 'med',
			value: 'med'
		},
		{
			label: 'nat',
			value: 'nat'
		},
		{
			label: 'phil',
			value: 'phil'
		},
		{
			label: 'rw',
			value: 'rw'
		},
		{
			label: 'tf',
			value: 'tf'
		}
	];

	const sortoptions = [
		{
			label: __('Title', 'rrze-lexikon'),
			value: 'title'
		},
		{
			label: __('ID', 'rrze-lexikon'),
			value: 'id'
		},
		{
			label: __('Sort field', 'rrze-lexikon'),
			value: 'sortfield'
		}
	];

	const orderoptions = [
		{
			label: __('ASC', 'rrze-lexikon'),
			value: 'ASC'
		},
		{
			label: __('DESC', 'rrze-lexikon'),
			value: 'DESC'
		}
	];

	// console.log('edit.js attributes: ' + JSON.stringify(attributes));

	const onChangeCategory = (newValues) => {
		setSelectedCategories(newValues);
		setAttributes({ category: String(newValues) })
	};

	const onChangeTag = (newValues) => {
		setSelectedTags(newValues);
		setAttributes({ tag: String(newValues) })
	};

	const onChangeID = (newValues) => {
		setSelectedIDs(newValues);
		setAttributes({ id: String(newValues) })
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'rrze-lexikon')}>
					<SelectControl
						label={__(
							"Categories",
							'rrze-lexikon'
						)}
						value={categorystate}
						options={categoryoptions}
						onChange={onChangeCategory}
						multiple
					/>
					<SelectControl
						label={__(
							"Tags",
							'rrze-lexikon'
						)}
						value={tagstate}
						options={tagoptions}
						onChange={onChangeTag}
						multiple
					/>
					<SelectControl
						label={__(
							"FAQ",
							'rrze-lexikon'
						)}
						value={idstate}
						options={faqoptions}
						onChange={onChangeID}
						multiple
					/>
					<SelectControl
						label={__(
							"Language",
							'rrze-lexikon'
						)}
						options={langoptions}
						onChange={(value) => setAttributes({ lang: value })}
					/>

				</PanelBody>
			</InspectorControls>
			<InspectorControls group="styles">
				<PanelBody title={__('Styles', 'rrze-lexikon')}>
					<SelectControl
						label={__(
							"Glossary Content",
							'rrze-lexikon'
						)}
						options={glossaryoptions}
						onChange={(value) => setAttributes({ glossary: value })}
					/>
					<SelectControl
						label={__(
							"Glossary Style",
							'rrze-lexikon'
						)}
						options={glossarystyleoptions}
						onChange={(value) => setAttributes({ glossarystyle: value })}
					/>
					<ToggleControl
						checked={!!hide_accordion}
						label={__(
							'Hide accordion',
							'rrze-lexikon'
						)}
						onChange={() =>
							setAttributes({
								hide_accordion: !hide_accordion,
							})
						}
					/>
					<ToggleControl
						checked={!!hide_title}
						label={__(
							'Hide title',
							'rrze-lexikon'
						)}
						onChange={() =>
							setAttributes({
								hide_title: !hide_title,
							})
						}
					/>
					<ToggleControl
						checked={!!expand_all_link}
						label={__(
							'Show "expand all" button',
							'rrze-lexikon'
						)}
						onChange={() =>
							setAttributes({
								expand_all_link: !expand_all_link,
							})
						}
					/>
					<ToggleControl
						checked={!!load_open}
						label={__(
							'Load website with opened accordions',
							'rrze-lexikon'
						)}
						onChange={() =>
							setAttributes({
								load_open: !load_open,
							})
						}
					/>
					<SelectControl
						label={__(
							"Color",
							'rrze-lexikon'
						)}
						options={coloroptions}
						onChange={(value) => setAttributes({ color: value })}
					/>
					<TextControl
						label={__(
							"Additional CSS-class(es) for sourrounding DIV",
							'rrze-lexikon'
						)}
						onChange={(value) => setAttributes({ additional_class: value })}
					/>
					<SelectControl
						label={__(
							"Sort",
							'rrze-lexikon'
						)}
						options={sortoptions}
						onChange={(value) => setAttributes({ sort: value })}
					/>
					<SelectControl
						label={__(
							"Order",
							'rrze-lexikon'
						)}
						options={orderoptions}
						onChange={(value) => setAttributes({ order: value })}
					/>
					<RangeControl
						label={__(
							"Heading starts with...",
							'rrze-lexikon'
						)}
						onChange={(value) => setAttributes({ hstart: value })}
						min={2}
						max={6}
						initialPosition={2}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<ServerSideRender
					block="create-block/rrze-lexikon"
					attributes={attributes}
				/>
			</div>
		</>
	);
}